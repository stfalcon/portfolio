<?php

namespace Application\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function testHomePage()
    {
        $this->loadFixtures(array(
                    'Application\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
                    'Application\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
                ));

        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check links to view projects on homepage
        $this->assertTrue($crawler->filter('a[href="/portfolio/web-development/preorder-it"]')->count() == 1);
        $this->assertTrue($crawler->filter('a[href="/portfolio/web-development/eprice-kz"]')->count() == 1);
    }

    public function testContactPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/contacts');

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check phone number
        $this->assertTrue($crawler->filter('html:contains("+380 97 874-03-42")')->count() > 0);
        // check e-mail
        $this->assertTrue($crawler->filter('html:contains("info@stfalcon.com")')->count() > 0);
    }
}