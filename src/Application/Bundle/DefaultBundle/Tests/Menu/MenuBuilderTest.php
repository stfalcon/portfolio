<?php

namespace Application\Bundle\DefaultBundle\Tests\Menu;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for MenuBuilder
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class MenuBuilderTest extends WebTestCase
{

    /**
     * Test main menu on the default bundle pages
     */
    public function testMainMenu()
    {
        $client = $this->createClient();

        /** check homepage **/
        $crawler = $client->request('GET', $this->getUrl('homepage'));
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check the current item of the menu
        // check menu in header and footer
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Проекты")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Услуги")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Команда")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Блог")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Контакты")'));


        /** check blog default page **/
        $crawler = $client->request('GET', $this->getUrl('blog'));
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check the current item of the menu
        // check menu in header and footer
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Проекты")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Услуги")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Команда")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li.current a:contains("Блог")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Контакты")'));


        /** check blog default page **/
        $crawler = $client->request('GET', $this->getUrl('contacts'));
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check the current item of the menu
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Проекты")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Услуги")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Команда")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li a:contains("Блог")'));
        $this->assertCount(2, $crawler->filter('.main-nav ul li.current a:contains("Контакты")'));
    }
}