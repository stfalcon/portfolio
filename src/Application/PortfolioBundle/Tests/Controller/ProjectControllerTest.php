<?php

namespace Application\PortfolioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/portfolio');

        $this->assertTrue($crawler->filter('html:contains("stfalcon-studio")')->count() > 0);
    }
}
