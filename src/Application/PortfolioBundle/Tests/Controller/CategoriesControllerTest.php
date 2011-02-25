<?php

namespace Application\PortfolioBundle\Tests\Controller;

//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{

//    public function testEmptyCategorysList()
//    {
//        $this->loadFixtures(array('Application\PortfolioBundle\DataFixtures\ORM\LoadCategoriesAndProjectsData'));
//
//        $client = $this->createClient();
//        $crawler = $client->request('GET', '/portfolio/categories');
//
//        $this->assertTrue($crawler->filter('html:contains("Web Development")')->count() > 0);
//    }

    public function testCategorysList()
    {
        $this->loadFixtures(array('Application\PortfolioBundle\DataFixtures\ORM\LoadCategoriesAndProjectsData'), false);

        $client = $this->createClient();
        $crawler = $client->request('GET', '/portfolio/categories');

        $this->assertTrue($crawler->filter('html:contains("Web Development")')->count() > 0);
    }

}
