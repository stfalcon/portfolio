<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for CategoryController
 */
class CategoryControllerTest extends WebTestCase{

    public function testViewCategory() {
        $this->loadFixtures(array('Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData'));
        $crawler = $this->fetchCrawler(
                $this->getUrl('portfolio_category_view', array('slug' => 'web-development')), 'GET', true, true
        );

        $this->assertEquals(1, $crawler->filter('html:contains("Web Development")')->count());
        $description = "In work we use Symfony2.";
        $this->assertEquals(1, $crawler->filter('html:contains("' . $description . '")')->count());
    }

    public function testViewNonExistCategory() {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('portfolio_category_view', array('slug' => 'web-design')));

        // check 404
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testPortfolioPagination() {
        $this->loadFixtures(array(
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData'
        ));

        $client = static::createClient();

        $crawlerFirstPage = $this->fetchCrawler(
            $this->getUrl(
                'portfolio_category_view',
                array('slug' => 'web-development')
            ), 'GET', true, true);

        $this->assertCount(1, $crawlerFirstPage->filter('div.pagination'));
        $this->assertCount(6, $crawlerFirstPage->filter('ul.projects li'));

        // click link to second page and check posts on second page
        $crawlerSecondPage = $client->click($crawlerFirstPage->filter('span.next a')->link());
        $this->assertEquals($this->getUrl('portfolio_category_view', array('slug' => 'web-development', 'page' => 2)),
        $client->getRequest()->getRequestUri());
        $this->assertCount(6, $crawlerSecondPage->filter('ul.projects li'));
    }

}
