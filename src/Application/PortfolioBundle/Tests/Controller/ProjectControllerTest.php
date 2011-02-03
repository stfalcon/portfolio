<?php

namespace Application\PortfolioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    public function testEmptyProjectsList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/portfolio/projects');

        $this->assertTrue($crawler->filter('html:contains("List of projects is empty")')->count() > 0);
    }

//    public function testCreateValidProject()
//    {
//        $client = $this->createClient();
//        $crawler = $client->request('GET', '/portfolio/project/create');
//
//        $form = $crawler->selectButton('Send');
//
//        $form['project[name]'] = 'preorder.it';
//        $form['project[description]'] = 'Press-releases and reviews of the latest electronic novelties: mobile phones, cellphones, smartphones, laptops, tablets, netbooks, gadgets, e-books, photo and video cameras. The possibility to leave a pre-order.';
//
//        $crawler = $client->submit($form);
//    }

}
