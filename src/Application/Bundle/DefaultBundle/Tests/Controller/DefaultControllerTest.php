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
                    'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadCategoryData',
                    'Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM\LoadProjectData',
                ));

        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());

        // check links to view projects on homepage
        $preorderUrl = $this->getUrl('portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'preorder-it')
        );
        $this->assertFalse($crawler->filter('a[href="' . $preorderUrl . '"]')->count() == 1);

        $epriceUrl = $this->getUrl('portfolio_project_view',
            array('categorySlug' => 'web-development', 'projectSlug' => 'eprice-kz')
        );
        $this->assertTrue($crawler->filter('a[href="' . $epriceUrl . '"]')->count() == 1);

        // check link to category view
        $url = $this->getUrl('portfolio_category_view', array('slug' => 'web-development'));
        $this->assertTrue($crawler->filter('a[href="' . $url . '"]')->count() == 1); // count of project > 3
    }

    public function testContactPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', $this->getUrl('contacts', array()));

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check phone number
        $this->assertTrue($crawler->filter('html:contains("+380 97 874-03-42")')->count() > 0);
        // check e-mail
        $this->assertTrue($crawler->filter('html:contains("info@stfalcon.com")')->count() > 0);
    }

}