<?php

namespace Application\Bundle\DefaultBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for DefaultContoller
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class DefaultControllerTest extends WebTestCase
{

    public function testHomePage()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
            'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
        ));

        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());

        // check link to category view
        $url = $this->getUrl('portfolio_all_projects');
        $this->assertCount(2, $crawler->filter('a[href="' . $url . '"]'));

        // check links to view projects on homepage
        $preorderUrl = $this->getUrl('portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'preorder-it')
        );
        $this->assertCount(1, $crawler->filter('.projects li a[href="' . $preorderUrl . '"]'));

        $epriceUrl = $this->getUrl('portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'eprice-kz')
        );
        $this->assertCount(1, $crawler->filter('.projects li a[href="' . $epriceUrl . '"]'));
    }

    public function testContactPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', $this->getUrl('contacts', array()));

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check phone number
        $this->assertCount(1, $crawler->filter('section.contacts-wrapper .info-group a:contains("+380 97 874-03-42")'));
        // check e-mail
        $this->assertCount(1, $crawler->filter('section.contacts-wrapper .info-group a:contains("info@stfalcon.com")'));
    }

}