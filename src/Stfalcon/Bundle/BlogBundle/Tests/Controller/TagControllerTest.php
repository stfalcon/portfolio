<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for TagController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagControllerTest extends WebTestCase
{

    public function testViewTag()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog_tag_view', array('text' => 'symfony2')));

        // check all posts count
        $this->assertCount(2, $crawler->filter('div.post'));

        // find first post and check fields
        $crawlerFirstPost = $crawler->filter('div.post')->first();
        // check display post title
        $this->assertCount(1, $crawlerFirstPost->filter('h3:contains("My first post")'));
        // check display post text
        $this->assertCount(1, $crawlerFirstPost->filter('p:contains("In work we use Symfony2.")'));
        // check display link to post
        $url = $this->getUrl('blog_post_view', array('slug' => 'my-first-post'));
        $this->assertCount(1, $crawlerFirstPost->filter('h3 a[href="' . $url . '"]'));
        // check post tags
        $this->assertCount(1, $crawlerFirstPost->filter('ul.tags:contains("symfony2")'));
        $this->assertCount(1, $crawlerFirstPost->filter('ul.tags:contains("doctrine2")'));
    }

    public function testViewNotExistTag()
    {
        $client = $this->makeClient();
        $client->request('GET', $this->getUrl('blog_tag_view', array('text' => 'not-exist-tag')));

        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testTagPagination()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostPaginatorData'));

        $client = static::createClient();

        // check posts and paginator on first page
        $url = $this->getUrl('blog_tag_view', array('text' => 'php'));
        $crawlerFirstPage = $client->request('GET', $url);
        $this->assertCount(1, $crawlerFirstPage->filter('div.pagination'));
        $this->assertCount(10, $crawlerFirstPage->filter('div.post'));
        $this->assertCount(1, $crawlerFirstPage->filter('div.post')->first()
            ->filter('h3:contains("Post for paginator #1")'));

        // click link to second page and check posts on second page
        $crawlerSecondPage = $client->click($crawlerFirstPage->filter('span.next a')->link());
        $this->assertEquals(
            $this->getUrl('blog_tag_view', array('text' => 'php', 'page' => 2)),
            $client->getRequest()->getRequestUri()
        );
        $this->assertCount(2, $crawlerSecondPage->filter('div.post'));
        $this->assertCount(1, $crawlerSecondPage->filter('div.post')->first()
            ->filter('h3:contains("Post for paginator #11")'));
    }

}