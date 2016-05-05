<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * Test cases for CategoryAdmin
 */
class CategoryAdminTest extends WebTestCase
{
    /** @var Client */
    protected $client;

    public function __construct()
    {
        $this->client = $this->makeClient();
    }

    public function testEmptyCategoriesList()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));
        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_list', array()));

        // check display notice
        $this->assertEquals(1, $crawler->filter('div.sonata-ba-list:contains("No result")')->count());
    }

    public function testCategoriesList()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'
        ));
        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_list', array()));

        // check display categories list
        $this->assertEquals(1, $crawler->filter('table.table tbody tr td:contains("web-development")')->count());
    }

    public function testCreateValidCategory()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));
        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_create', array()));

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $form[$formId . '[translations][defaultLocale][ru][name]'] = 'Web Design';
        $form[$formId . '[translations][defaultLocale][ru][shortName]'] = 'Web Design';
        $form[$formId . '[translations][defaultLocale][ru][description]'] = 'Short text about web design servise.';
        $form[$formId . '[translations][defaultLocale][ru][details]'] = 'Short text about web design servise.';
        $form[$formId . '[translations][defaultLocale][ru][title]'] = 'Meta Title';
        $form[$formId . '[translations][translations][en][title]'] = 'Meta Title';
        $form[$formId . '[slug]'] = 'web-design';
        $form[$formId . '[cost]'] = '20 000';
        $this->client->submit($form);

        // check responce
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertFalse($this->client->getResponse()->isRedirect());

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_list', array()));

        // check display new category in list
        $this->assertEquals(1, $crawler->filter('table.table tbody tr td:contains("web-design")')->count());
    }

    public function testCreateInvalidCategory()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));
        $this->doLogin();

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_create', array()));
        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $form[$formId . '[translations][defaultLocale][ru][name]'] = ''; // should not be blank
        $form[$formId . '[translations][defaultLocale][ru][description]'] = ''; // should not be blank
        $form[$formId . '[slug]'] = ''; // should not be blank
        $crawler = $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testEditCategory()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'
        ));
        $this->doLogin();

        $em = $this->getContainer()->get('doctrine')->getManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_edit', array('id' => $category->getId())));
        $form = $crawler->selectButton('btn_update_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $form[$formId . '[translations][defaultLocale][ru][name]'] = 'Web Design';
        $form[$formId . '[translations][defaultLocale][ru][description]'] = 'Short text about web design servise.';
        $form[$formId . '[translations][defaultLocale][ru][shortName]'] = 'Web Design';
        $form[$formId . '[translations][defaultLocale][ru][details]'] = 'Short text about web design servise.';
        $form[$formId . '[translations][defaultLocale][ru][title]'] = 'Meta Title';
        $form[$formId . '[translations][translations][en][title]'] = 'Meta Title';
        $form[$formId . '[slug]'] = 'web-design';
        $this->client->submit($form);

        // check responce
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertFalse($this->client->getResponse()->isRedirect());

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_list', array()));
        $this->assertEquals(1, $crawler->filter('table.table tbody tr td:contains("web-design")')->count());
    }

    public function testEditInvalidCategory()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'
        ));
        $this->doLogin();

        $em = $this->getContainer()->get('doctrine')->getManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_edit', array('id' => $category->getId())));

        $form = $crawler->selectButton('btn_update_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $form[$formId . '[translations][defaultLocale][ru][name]'] = 'Web Design';
        $form[$formId . '[translations][defaultLocale][ru][description]'] = '';
        $form[$formId . '[slug]'] = 'web-design';
        $crawler = $this->client->submit($form);

        $this->assertFalse($this->client->getResponse()->isRedirect());
    }

    public function testEditNonExistCategory()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));
        $this->doLogin();

        $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_edit', array('id' => 0)));

        // check 404
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteCategory()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
        ));
        $this->doLogin();
        $em = $this->getContainer()->get('doctrine')->getManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));

        // delete category
        $crawler = $this->client->request('GET',
            $this->getUrl('admin_stfalcon_portfolio_category_delete', array('id' => $category->getId()))
        );
        $form = $crawler->selectButton('Yes, delete')->form();
        $this->client->submit($form);

        // check if category was removed from DB
        $em->detach($category);
        $categoryRemoved = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));
        $this->assertNull($categoryRemoved);
    }

    public function testDeleteNotExistCategory()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));
        $this->doLogin();
        $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_category_delete', array('id' => 0)));

        // check 404
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Do login with username
     *
     * @param string $username
     */
    private function doLogin($username = 'admin')
    {
        $crawler = $this->client->request('GET', $this->getUrl('fos_user_security_login', array()));
        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => $username,
            '_password' => 'qwerty'
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirects();
    }
}
