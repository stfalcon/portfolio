<?php

namespace StfalconBundle\Bundle\BlogBundle\Tests\Menu;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for MenuBuilder
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class MenuBuilderTest extends WebTestCase
{

    /**
     * Test breadcrumbs on the blog pages
     */
    public function testBreadcrumbsMenu()
    {
        $client = $this->createClient();

        $this->loadFixtures(array(
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'
        ));

        $blogListUrl = $this->getUrl('blog');

        /** check posts list page **/
        $crawler = $client->request('GET', $blogListUrl);
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check the current item of the menu
        $this->assertEquals(1, $crawler->filter('div.breadcrumbs>ul>li.current:contains("Блог")')->count());


        /** check post "Post about php" page **/
        $crawler = $client->request('GET', $this->getUrl('blog_post_view', array('slug' => 'my-first-post')));
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check the current item of the menu
        $this->assertEquals(1, $crawler->filter('div.breadcrumbs>ul>li.current:contains("My first post")')->count());

        /** check post "My first post" page **/
        $crawler = $client->request('GET', $this->getUrl('blog_post_view', array('slug' => 'post-about-php')));
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check the current item of the menu
        $this->assertEquals(1, $crawler->filter('div.breadcrumbs>ul>li.current:contains("Post about php ")')->count());


        // get the posts list page link in the breadcrumbs
        $blogListLink = $crawler->filter('div.breadcrumbs ul li a[href="' . $blogListUrl . '"]');
        // check the current item of the menu
        $this->assertEquals(1, $blogListLink->count());
        // click on it
        $crawler = $client->click($blogListLink->link());
        // check if a response is successful
        $this->assertTrue($client->getResponse()->isSuccessful());
        // check the current item of the menu
        $this->assertEquals(1, $crawler->filter('div.breadcrumbs>ul>li.current:contains("Блог")')->count());
    }
}