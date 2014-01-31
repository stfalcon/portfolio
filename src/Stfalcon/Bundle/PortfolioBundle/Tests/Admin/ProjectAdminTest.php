<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for ProjectAdmin
 */
class ProjectAdminTest extends WebTestCase
{

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
        $this->loadFixtures(array());
        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_project_list', array()), 'GET', true, true);

        // check don't display projects
        $this->assertEquals(0, $crawler->filter('table tbody tr')->count());
    }

    public function testProjectsList()
    {
        $this->loadFixtures(array(
                    'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
                    'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
                ));
        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_project_list', array()), 'GET', true, true);

        // check display projects
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("preorder.it")')->count());
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("eprice.kz")')->count());
        $this->assertEquals(16, $crawler->filter('table tbody tr td:contains("example.com")')->count());
    }

    public function testCreateValidProject()
    {
        $this->loadFixtures(array('Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'));
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('admin_bundle_portfolio_project_create', array()));

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
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('admin_bundle_portfolio_project_edit', array('id' => 1))));

        // @todo дальше лишние проверки. достаточно проверить или проект создался в БД
        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_project_list', array()), 'GET', true, true);
        // check display new category in list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("wallpaper.in.ua")')->count());
    }

    public function testCreateInvalidProject()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testEditProject()
    {
        // Stop here and mark this test as incomplete.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testDeleteProject()
    {
        $this->loadFixtures(array(
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        $client = $this->makeClient(true);
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $project = $em->getRepository("StfalconPortfolioBundle:Project")->findOneBy(array('slug' => 'preorder-it'));

        // delete project
        $client->request('POST', $this->getUrl('admin_bundle_portfolio_project_delete', array('id' => $project->getId())), array('_method' => 'DELETE'));

        // check if project was removed from DB
        $em->detach($project);
        $projectRemoved = $em->getRepository("StfalconPortfolioBundle:Project")->findOneBy(array('slug' => 'preorder-it'));
        $this->assertNull($projectRemoved);
    }

    public function testDeleteNotExistProject()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $client->request('POST', $this->getUrl('admin_bundle_portfolio_project_delete', array('id' => 0)));

        // check 404
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testProjectOrdering()
    {
        $this->loadFixtures(array(
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_project_list'), 'GET', true, true);

        $tds = $crawler->filter('table tbody tr');
        $tds->first();
        $this->assertContains('preorder.it', $tds->current()->textContent);

        $client = $this->makeClient(true);
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $project = $em->getRepository("StfalconPortfolioBundle:Project")->findOneBy(array('name' => 'preorder.it'));
        $crawler = $client->request('POST', $this->getUrl('portfolioProjectsApplyOrder'), array(
            'projects' => array(array('id' => $project->getId(), 'index' => 200))
            ));
        $this->assertEquals('good', $client->getResponse()->getContent());

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_portfolio_project_list'), 'GET', true, true);
        $tds = $crawler->filter('table tbody tr');
        $tds->last();
        $this->assertContains('preorder.it', $tds->current()->textContent);
    }

}