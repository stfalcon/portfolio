<?php

namespace Application\PortfolioBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ProjectControllerTest extends WebTestCase
{
    
    public function testEmptyProjectsList()
    {
        $this->loadFixtures(array(), false);

        $client = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ));
        $crawler = $client->request('GET', '/admin/portfolio/projects');

        // @todo: change assert
        $this->assertTrue($crawler->filter('html:contains("List of projects is empty")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("preorder.it")')->count() == 0);
    }

    public function testProjectsList()
    {
        $this->loadFixtures(array(
                    'Application\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
                    'Application\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
                ));

        $client = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ));
        $crawler = $client->request('GET', '/admin/portfolio/projects');

        $this->assertTrue($crawler->filter('html:contains("preorder.it")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("eprice.kz")')->count() > 0);
    }

    public function testCreateValidProject()
    {
        $client = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ));
        $crawler = $client->request('GET', '/admin/portfolio/project/create');

        $form = $crawler->selectButton('Send')->form();

        $form['project[name]'] = 'wallpaper.in.ua';
        $form['project[slug]'] = 'wallpaper-in-ua';
        $form['project[url]'] = 'http://wallpaper.in.ua';
        $form['project[description]'] = 'Free desktop wallpapers gallery.';
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('html:contains("wallpaper.in.ua")')->count() > 0);
    }

//    public function testCreateInvalidProject()
//    {
//    }
//
//    public function testEditProject()
//    {
//    }
//
    public function testDeleteProject()
    {
        // delete project
        $client = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ));
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/admin/portfolio/project/delete/preorder-it');

        // assertRedirect
//        $this->assertEquals(1, $client->getRedirectionsCount());
        // assertProjectsCount
        $this->assertTrue($crawler->filter('html:contains("preorder.it")')->count() > 0);
//        $this->assertTrue($crawler->filter('html:contains("eprice.kz")')->count() == 1);
    }

}
