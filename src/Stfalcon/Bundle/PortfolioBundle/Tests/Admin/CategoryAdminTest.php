<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for CategoryAdmin
 */
class CategoryAdminTest extends WebTestCase
{

    public function testEmptyCategoriesList()
    {
        $this->loadFixtures(array());
        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_category_list', array()), 'GET', true, true);

        // check display notice
        $this->assertEquals(0, $crawler->filter('table tbody tr')->count());
    }

    public function testCategoriesList()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'));
        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_category_list', array()), 'GET', true, true);

        // check display categories list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("web-development")')->count());
    }

    public function testCreateValidCategory()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('admin_bundle_portfolio_category_create', array()));

        $inputs = $crawler->filter('form input');
        $inputs->first();
        $formId = str_replace("_slug", "", $inputs->current()->getAttribute('id'));

        $form = $crawler->selectButton('btn_create_and_edit')->form();

        $form[$formId . '[name]'] = 'Web Design';
        $form[$formId . '[slug]'] = 'web-design';
        $form[$formId . '[description]'] = 'Short text about web design servise.';
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('admin_bundle_portfolio_category_edit', array('id' => 1))));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_category_list', array()), 'GET', true, true);

        // check display new category in list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("web-design")')->count());
    }

    public function testCreateInvalidCategory()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('admin_bundle_portfolio_category_create', array()));

        $inputs = $crawler->filter('form input');
        $inputs->first();
        $formId = str_replace("_slug", "", $inputs->current()->getAttribute('id'));

        $form = $crawler->selectButton('btn_create_and_edit')->form();

        $form[$formId . '[name]'] = ''; // should not be blank
        $form[$formId . '[slug]'] = ''; // should not be blank
        $form[$formId . '[description]'] = ''; // should not be blank
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertFalse($client->getResponse()->isRedirect());
    }

    public function testEditCategory()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'));
        $client = $this->makeClient(true);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));
        $crawler = $client->request('GET', $this->getUrl('admin_bundle_portfolio_category_edit', array('id' => $category->getId())));

        $inputs = $crawler->filter('form input');
        $inputs->first();
        $formId = str_replace("_slug", "", $inputs->current()->getAttribute('id'));

        $form = $crawler->selectButton('btn_update_and_edit')->form();

        $form[$formId . '[name]'] = 'Web Design';
        $form[$formId . '[slug]'] = 'web-design';
        $form[$formId . '[description]'] = 'Short text about web design servise.';
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('admin_bundle_portfolio_category_edit', array('id' => $category->getId()))));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_category_list', array()), 'GET', true, true);
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("web-design")')->count());
    }

    public function testEditInvalidCategory()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'));
        $client = $this->makeClient(true);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));
        $crawler = $client->request('GET', $this->getUrl('admin_bundle_portfolio_category_edit', array('id' => $category->getId())));

        $inputs = $crawler->filter('form input');
        $inputs->first();
        $formId = str_replace("_slug", "", $inputs->current()->getAttribute('id'));

        $form = $crawler->selectButton('btn_update_and_edit')->form();

        $form[$formId . '[name]'] = 'Web Design';
        $form[$formId . '[slug]'] = 'web-design';
        $form[$formId . '[description]'] = '';
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertFalse($client->getResponse()->isRedirect());
    }

    public function testEditNonExistCategory()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);

        $crawler = $client->request('GET', $this->getUrl('admin_bundle_portfolio_category_edit', array('id' => 0)));

        // check 404
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteCategory()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'));

        $client = $this->makeClient(true);
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));

        // delete category
        $crawler = $client->request('POST', $this->getUrl('admin_bundle_portfolio_category_delete', array('id' => $category->getId())), array('_method' => 'DELETE'));

        // check if category was removed from DB
        $em->detach($category);
        $categoryRemoved = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));
        $this->assertNull($categoryRemoved);
    }

    public function testDeleteNotExistCategory()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('POST', $this->getUrl('admin_bundle_portfolio_category_delete', array('id' => 0)));

        // check 404
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

}