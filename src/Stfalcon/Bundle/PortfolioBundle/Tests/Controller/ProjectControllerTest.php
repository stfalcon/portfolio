<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for ProjectController
 */
class ProjectControllerTest extends WebTestCase
{

    public function testViewProject()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        $crawler = $this->fetchCrawler(
            $this->getUrl(
                'portfolio_project_view',
                array('categorySlug' => 'web-development', 'projectSlug' => 'preorder-it')
            ), 'GET');

        $description = "Press-releases and reviews of the latest electronic novelties. The possibility to leave a pre-order.";

        // check display project info
        $this->assertEquals(1, $crawler->filter('aside.project-info h2:contains("preorder.it")')->count());
        $this->assertEquals(1, $crawler->filter('aside.project-info:contains("' . $description . '")')->count());
        $this->assertEquals(1, $crawler->filter('aside.project-info a[href="http://preorder.it"]')->count());

        $epriceUrl = $this->getUrl(
            'portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'eprice-kz')
        );
        // check display prev/next project url
        $this->assertEquals(2, $crawler->filter('div.projects-nav a[href="' . $epriceUrl . '"]')->count());
    }

    public function testFilledProjectUsersList()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        // Check project preorder.it
        $urlProjectView = $this->getUrl('portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'preorder-it'));
        $crawler = $this->fetchCrawler($urlProjectView, 'GET');

        // check display project info
        $this->assertEquals(1, $crawler->filter('section.work-on-project h2:contains("Над проектом работали")')->count());
        $this->assertEquals(1, $crawler->filter('.team-list li:contains("Admin User")')->count());
        $this->assertEquals(1, $crawler->filter('.team-list li:contains("Second User")')->count());
    }

    public function testEmptyProjectUsersList()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        // Check project eprice.kz
        $url = $this->getUrl('portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'eprice-kz'));
        $crawler = $this->fetchCrawler($url, 'GET');

        // check display project info
        $this->assertEquals(0, $crawler->filter('section.work-on-project h2:contains("Над проектом работали")')->count());
        $this->assertEquals(0, $crawler->filter('.team-list li')->count());
    }
}