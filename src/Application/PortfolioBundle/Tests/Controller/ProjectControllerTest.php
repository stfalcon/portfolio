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
//        var_dump($client->getResponse()->getContent());
//        exit;

//        $crawler = $this->createClient()->request('GET', '/portfolio/projects');
//        $this->assertTrue($crawler->filter('html:contains("preorder")')->count() > 0);
    }

//    public function testProjectCreate()
//    {
//        $client = $this->createClient();
//
//        $crawler = $client->request('GET', '/portfolio/projects/create');
//        $form = $crawler->selectButton('submit');
//
//        // set some values
//        $form['name'] = 'preorder.it';
//        $form['description'] = 'project description';
//
//        // submit the form
//        $crawler = $client->submit($form);
//
////        $this->assertTrue(count($crawler->filter('h1')) > 0);
//    }
}
