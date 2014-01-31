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
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        $crawler = $this->fetchCrawler(
            $this->getUrl(
                'portfolio_project_view',
                array('categorySlug' => 'web-development', 'projectSlug' => 'preorder-it')
            ), 'GET', true, true);

        $description = "Press-releases and reviews of the latest electronic novelties. The possibility to leave a pre-order.";

        // check display project info
        $this->assertEquals(1, $crawler->filter('html:contains("preorder.it")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $description . '")')->count());
        $this->assertEquals(1, $crawler->filter('a[href="http://preorder.it"]')->count());

        $epriceUrl = $this->getUrl(
            'portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'eprice-kz')
        );
        // check display prev/next project url
        $this->assertEquals(1, $crawler->filter('#content a[href="' . $epriceUrl . '"]')->count());

        // check display projects in services widget
//        $this->assertEquals(1, $crawler->filter('#sidebar a[href="' . $epriceUrl . '"]')->count());
    }

    public function testFilledProjectUsersList()
    {
        $this->loadFixtures(array(
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        // Check project preorder.it
        $urlProjectView = $this->getUrl('portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'preorder-it'));
        $crawler = $this->fetchCrawler($urlProjectView, 'GET', true, true);

        // check display project info
        $this->assertEquals(1, $crawler->filter('html:contains("Над проектом работали")')->count());
        $this->assertEquals(1, $crawler->filter('html #sidebar dl>dt:contains("art-director and designer")')->count());
    }

    public function testEmptyProjectUsersList()
    {
        $this->loadFixtures(array(
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        // Check project eprice.kz
        $url = $this->getUrl('portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'eprice-kz'));
        $crawler = $this->fetchCrawler($url, 'GET', true, true);

        // check display project info
        $this->assertEquals(0, $crawler->filter('html:contains("Над проектом работали")')->count());
        $this->assertEquals(0, $crawler->filter('html #sidebar dl>dt:contains("art-director and designer")')->count());
    }
}