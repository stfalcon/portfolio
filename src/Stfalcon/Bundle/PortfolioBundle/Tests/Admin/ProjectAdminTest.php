<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * Test cases for ProjectAdmin
 */
class ProjectAdminTest extends WebTestCase
{
    /** @var Client */
    protected $client;

    public function __construct()
    {
        $this->client = $this->makeClient();
    }

    /**
     * Get path to test project image
     *
     * @return string
     */
    private function _getTestImagePath()
    {
        return \realpath(__DIR__ . '/../Entity/Resources/files/projects/preorder-it/data/index.png');
    }

    public function testEmptyProjectsList()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));
        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_list', array()));

        // check don't display projects
        $this->assertEquals(1, $crawler->filter('div.sonata-ba-list:contains("Нет результатов")')->count());
    }

    public function testProjectsList()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));
        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_list', array()));

        // check display projects
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("preorder.it")')->count());
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("eprice.kz")')->count());
        $this->assertEquals(16, $crawler->filter('table tbody tr td:contains("example.com")')->count());
    }

    public function testCreateValidProject()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'
        ));
        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_create', array()));

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));


        $form[$formId . '[name]'] = 'wallpaper.in.ua';
        $form[$formId . '[slug]'] = 'wallpaper-in-ua';
        $form[$formId . '[url]'] = 'http://wallpaper.in.ua';
        $form[$formId . '[imageFile]'] = $this->_getTestImagePath();
        $form[$formId . '[description]'] = 'Free desktop wallpapers gallery.';
        $form[$formId . '[users]'] = 'users';
        $form[$formId . '[categories]']->select(array($category->getId()));
        $form[$formId . '[onFrontPage]'] = 1;
        $this->client->submit($form);

        // check responce
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertFalse($this->client->getResponse()->isRedirect());

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_list', array()));
        // check display new category in list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("wallpaper.in.ua")')->count());
    }

    public function testCreateInvalidProject()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'
        ));
        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_create', array()));

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));


        $form[$formId . '[name]'] = '';
        $form[$formId . '[slug]'] = '';
        $form[$formId . '[url]'] = 'http://wallpaper.in.ua';
        $form[$formId . '[imageFile]'] = $this->_getTestImagePath();
        $form[$formId . '[description]'] = 'Free desktop wallpapers gallery.';
        $form[$formId . '[users]'] = 'users';
        $form[$formId . '[categories]']->select(array($category->getId()));
        $form[$formId . '[onFrontPage]'] = 1;
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->filter('.alert-error:contains("При создании элемента произошла ошибка.")')->count());
    }

    public function testEditProject()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData'
        ));
        $this->doLogin();
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $project = $em->getRepository("StfalconPortfolioBundle:Project")->findOneBy(array('slug' => 'preorder-it'));
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_edit', array('id' => $project->getId())));

        $form = $crawler->selectButton('btn_update_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $category = $em->getRepository("StfalconPortfolioBundle:Category")->findOneBy(array('slug' => 'web-development'));


        $form[$formId . '[name]'] = 'wallpaper.in.ua';
        $form[$formId . '[slug]'] = 'wallpaper-in-ua';
        $form[$formId . '[url]'] = 'http://wallpaper.in.ua';
        $form[$formId . '[imageFile]'] = $this->_getTestImagePath();
        $form[$formId . '[description]'] = 'Free desktop wallpapers gallery.';
        $form[$formId . '[users]'] = 'users';
        $form[$formId . '[categories]']->select(array($category->getId()));
        $form[$formId . '[onFrontPage]'] = 1;
        $this->client->submit($form);

        // check responce
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertFalse($this->client->getResponse()->isRedirect());

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_list', array()));
        // check display new category in list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("wallpaper.in.ua")')->count());
    }

    public function testDeleteProject()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));
        $this->doLogin();
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $project = $em->getRepository("StfalconPortfolioBundle:Project")->findOneBy(array('slug' => 'preorder-it'));

        // delete project
        $crawler = $this->client->request('GET',
            $this->getUrl('admin_stfalcon_portfolio_project_delete', array('id' => $project->getId()))
        );
        $form = $crawler->selectButton('Да, удалить')->form();
        $this->client->submit($form);

        // check if project was removed from DB
        $em->detach($project);
        $projectRemoved = $em->getRepository("StfalconPortfolioBundle:Project")->findOneBy(array('slug' => 'preorder-it'));
        $this->assertNull($projectRemoved);
    }

    public function testDeleteNotExistProject()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));
        $this->doLogin();
        $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_delete', array('id' => 0)));

        // check 404
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testProjectOrdering()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));
        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_list'));

        $tds = $crawler->filter('table tbody tr');
        $tds->first();
        $this->assertContains('preorder.it', $tds->current()->textContent);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $project = $em->getRepository("StfalconPortfolioBundle:Project")->findOneBy(array('name' => 'preorder.it'));
        $this->client->request('POST', $this->getUrl('portfolioProjectsApplyOrder'), array(
            'projects' => array(array('id' => $project->getId(), 'index' => 200))
            ));
        $this->assertEquals('good', $this->client->getResponse()->getContent());

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_portfolio_project_list'));
        $tds = $crawler->filter('table tbody tr');
        $tds->last();
        $this->assertContains('preorder.it', $tds->current()->textContent);
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