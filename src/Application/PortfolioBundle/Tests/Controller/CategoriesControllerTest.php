<?php

namespace Application\PortfolioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testEmptyCategorysList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/portfolio/categories');

        $this->assertTrue($crawler->filter('html:contains("List of categories is empty")')->count() > 0);
    }

}
