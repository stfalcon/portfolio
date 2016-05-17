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
    /**
     * Test for home page
     */
    public function testHomePage()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));

        $client = $this->createClient();
        $crawler = $client->request('GET', $this->getUrl('homepage', array()));

        // check response
        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->assertCount(1, $crawler->filter('h1:contains("Stfalcon — Studio of web design and sites development")'));
        $this->assertCount(1, $crawler->filter('h2:contains("We Code Your Ideas")'));

        $this->assertCount(1, $crawler->filter('h2:contains("We contribute to Open Source")'));
        $this->assertCount(1, $crawler->filter('h2:contains("We organize conferences")'));
        $this->assertCount(1, $crawler->filter('h2:contains("Fresh from our Blog")'));
        $this->assertCount(1, $crawler->filter('form.subscribe-form'));

        $this->assertCount(1, $crawler->filter('.footer .contact-list a:contains("+380 67 334-40-40")'));
        $this->assertCount(1, $crawler->filter('.footer .contact-list span.adress:contains("3/2 Zarichanska str., Khmelnytskyi, Ukraine")'));
    }

    /**
     * Test for contacts page
     */
    public function testContactPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', $this->getUrl('contacts', array()));

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check phone number
        $this->assertCount(1, $crawler->filter('section.contacts-wrapper .info-group a:contains("+380 67 334-40-40")'));
        // check e-mail
        $this->assertCount(1, $crawler->filter('section.contacts-wrapper .info-group a:contains("info@stfalcon.com")'));
    }

}
