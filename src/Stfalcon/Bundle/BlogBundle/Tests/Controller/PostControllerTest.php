<?php

namespace Stfalcon\Bundle\BlogBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for PostController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostControllerTest extends WebTestCase
{

    public function testViewPost()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog_post_view', array('slug' => 'my-first-post')));

        // check display post title
        $this->assertCount(1, $crawler->filter('div.post h1:contains("My first post")'));
        // check display post text
        $this->assertCount(1, $crawler->filter('div.post:contains("In work we use Symfony2.")'));
        // and find <span id="more">
        $this->assertCount(1, $crawler->filter('div.post span#more'));

        // check post tags
        $this->assertCount(1, $crawler->filter('div.post ul.tags:contains("symfony2")'));
        $this->assertCount(1, $crawler->filter('div.post ul.tags:contains("doctrine2")'));
    }

    public function testViewNotExistPost()
    {
        $client = $this->makeClient();
        $client->request('GET', $this->getUrl('blog_post_view', array('slug' => 'not-exist-post')));

        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testViewBlog()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog'));

        // check post count
        $this->assertCount(2, $crawler->filter('div.post'));

        $firstUrl = $this->getUrl('blog_post_view', array('slug' => 'my-first-post'));
        $secondUrl = $this->getUrl('blog_post_view', array('slug' => 'post-about-php'));

        // check links to posts
        $this->assertCount(1, $crawler->filter('div.post h3 a[href="' . $firstUrl . '"]'));
        $this->assertCount(1, $crawler->filter('div.post h3 a[href="' . $secondUrl . '"]'));

        // check exist read more tag
        $this->assertCount(0, $crawler->filter('div.post:contains("<!--more-->")'));

        // check link to read more
        $this->assertCount(1, $crawler->filter('div.post a[href="' . $firstUrl . '#more"]'));
        $this->assertCount(0, $crawler->filter('div.post a[href="' . $secondUrl . '#more"]'));

        // check exist posts tags
        $this->assertCount(1, $crawler->filter('div.post ul.tags:contains("php")'));
        $this->assertCount(2, $crawler->filter('div.post ul.tags:contains("symfony2")'));
        $this->assertCount(1, $crawler->filter('div.post ul.tags:contains("doctrine2")'));

        // check links to posts commets
        $this->assertCount(1, $crawler->filter('div.post a[href="' . $firstUrl . '#disqus_thread"]'));
        $this->assertCount(1, $crawler->filter('div.post a[href="' . $secondUrl . '#disqus_thread"]'));
    }

    public function testBlogPagination()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostPaginatorData'));
        $client = static::createClient();

        // check posts and paginator on first page
        $crawlerFirstPage = $client->request('GET', $this->getUrl('blog'));
        $this->assertCount(1, $crawlerFirstPage->filter('div.pagination'));
        $this->assertCount(10, $crawlerFirstPage->filter('div.post'));
        $this->assertCount(1, $crawlerFirstPage->filter('div.post')->first()
            ->filter('h3:contains("Post for paginator #1")'));

        // click link to second page and check posts on second page
        $crawlerSecondPage = $client->click($crawlerFirstPage->filter('span.next a')->link());
        $this->assertEquals($this->getUrl('blog', array('page' => 2)),
            $client->getRequest()->getRequestUri());
        $this->assertCount(2, $crawlerSecondPage->filter('div.post'));
        $this->assertCount(1, $crawlerSecondPage->filter('div.post')->first()
            ->filter('h3:contains("Post for paginator #11")'));
    }

}