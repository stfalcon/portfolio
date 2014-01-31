<?php

namespace StfalconBundle\Bundle\PortfolioBundle\Tests\Menu;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for MenuBuilder
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class MenuBuilderTest extends WebTestCase
{

    /**
     * Test breadcrumbs on the projects pages
     */
    public function testBreadcrumbsMenu()
    {
        $this->loadFixtures(array(
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        /** check page of the "preorder.it" project **/
        $this->_testBreadcrumbsMenuForProject('preorder-it', 'preorder.it');

        /** check page of the "eprice.kz" project **/
        $this->_testBreadcrumbsMenuForProject('eprice-kz', 'eprice.kz');
    }

    /**
     * Check breadcrumbs statements for project
     *
     * @param string $projectSlug A slug of the project
     * @param string $itemContent Content of the current breadcrumbs' item
     */
    protected function _testBreadcrumbsMenuForProject($projectSlug, $itemContent)
    {
        $client = $this->createClient();

        $projectListUrl = $this->getUrl('portfolio_category_view', array('slug' => 'web-development'));

        $crawler = $client->request('GET', $this->getUrl('portfolio_project_view', array(
            'categorySlug' => 'web-development',
            'projectSlug'  => $projectSlug
        )));
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // get the projects list page link in the breadcrumbs
        $projectListLink = $crawler->filter('div.breadcrumbs ul li a[href="' . $projectListUrl . '"]');
        // check the current item of the menu
        $this->assertEquals(1, $projectListLink->count());
        // check the current item of the menu
        $this->assertEquals(1,
            $crawler->filter('div.breadcrumbs>ul>li.current:contains("' . $itemContent . '")')->count()
        );
        // click on it
        $crawler = $client->click($projectListLink->link());
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // get the projects list page link in the breadcrumbs
        $projectListLink = $crawler->filter('div.breadcrumbs ul li a[href="' . $projectListUrl . '"]');
        // check the current item of the menu
        $this->assertEquals(0, $projectListLink->count());
        $this->assertEquals(1, $crawler->filter('div.breadcrumbs>ul>li.current:contains("Web Development")')->count());
    }
}