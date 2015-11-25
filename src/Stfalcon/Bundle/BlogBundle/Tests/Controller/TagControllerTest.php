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
                'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagTranslationData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('blog_tag_view', array('text' => 'symfony2')));

        // check all posts count
        $this->assertCount(2, $crawler->filter('article.blog-post'));

        // find first post and check fields
        $crawlerFirstPost = $crawler->filter('article.blog-post')->first();
        // check display post title
        $this->assertCount(1, $crawlerFirstPost->filter('h1 a:contains("My first post")'));
        // check display post text
        $this->assertCount(1, $crawlerFirstPost->filter('div.post-content p:contains("In work we use Symfony2.")'));
        // check display link to post
        $url = $this->getUrl('blog_post_view', array('slug' => 'my-first-post'));
        $this->assertCount(1, $crawlerFirstPost->filter('h1 a[href="' . $url . '"]'));
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
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostPaginatorData'));

        $client = static::createClient();

        // check posts and paginator on first page
        $url = $this->getUrl('blog_tag_view', array('text' => 'php'));
        $crawlerFirstPage = $client->request('GET', $url);
        $this->assertCount(1, $crawlerFirstPage->filter('nav.pagination'));
        $this->assertCount(10, $crawlerFirstPage->filter('article.blog-post'));
        $this->assertCount(1, $crawlerFirstPage->filter('article.blog-post')->first()
            ->filter('h1 a:contains("Post for paginator #1")'));

        // click link to second page and check posts on second page
        $crawlerSecondPage = $client->click($crawlerFirstPage->filter('nav.pagination li a:contains("2")')->link());
        $this->assertEquals(
            $this->getUrl('blog_tag_view', array('text' => 'php', 'page' => 2)),
            $client->getRequest()->getRequestUri()
        );
        $this->assertCount(2, $crawlerSecondPage->filter('article.blog-post'));
        $this->assertCount(1, $crawlerSecondPage->filter('article.blog-post')->first()
            ->filter('h1 a:contains("Post for paginator #2")'));
    }

}
