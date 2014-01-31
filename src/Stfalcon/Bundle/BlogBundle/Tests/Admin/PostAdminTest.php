<?php

namespace StfalconBundle\Bundle\BlogBundle\Tests\Admin;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Test cases for PostAdmin
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostAdminTest extends WebTestCase
{
    public function testEmptyPostsListForAdmin()
    {
        $this->loadFixtures(array());
        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_blog_post_list', array()), 'GET', true, true);

        // check don't display categories
        $this->assertEquals(0, $crawler->filter('table tbody tr')->count());
    }

    public function testCreateNewPost()
    {
        $this->loadFixtures(array());
        $client = $this->makeClient(true);
        $crawler = $client->request('GET', $this->getUrl('admin_bundle_blog_post_create', array()));

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $form[$formId . '[title]'] = 'Post title';
        $form[$formId . '[slug]'] = 'post-slug';
        $form[$formId . '[text]'] = 'Post text';
        $form[$formId . '[tags]'] = 'Post,tags';
        $crawler = $client->submit($form);

        // check redirect to list of post
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('admin_bundle_blog_post_edit', array('id' => 1) )));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_blog_post_list', array()), 'GET', true, true);
        // check display new category in list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("Post title")')->count());
    }

    public function testNotEmptyPostListForAdmin()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_blog_post_list', array()), 'GET', true, true);

        // check display posts list
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("My first post")')->count());
    }

    public function testEditPost()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $client = $this->makeClient(true);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $post = $em->getRepository("StfalconBlogBundle:Post")->findOneBy(array('slug' => 'post-about-php'));

        $crawler = $client->request('GET', $this->getUrl('admin_bundle_blog_post_edit', array('id' => $post->getId())));

        $form = $crawler->selectButton('btn_update_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $form[$formId . '[title]'] = 'New post title';
        $form[$formId . '[slug]'] = 'new-post-slug';
        $form[$formId . '[text]'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..';
        $form[$formId . '[tags]'] = 'php, symfony2, etc';
        $crawler = $client->submit($form);

        // check redirect to list of categories
        $this->assertTrue($client->getResponse()->isRedirect($this->getUrl('admin_bundle_blog_post_edit', array('id' => $post->getId()) )));

        $crawler = $client->followRedirect();

        // check responce
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertFalse($client->getResponse()->isRedirect());

        $crawler = $this->fetchCrawler($this->getUrl('admin_bundle_blog_post_list', array()), 'GET', true, true);
        $this->assertEquals(1, $crawler->filter('table tbody tr td:contains("New post title")')->count());
    }

    public function testDeletePost()
    {
        $this->loadFixtures(array(
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
                'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));
        $client = $this->makeClient(true);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $post = $em->getRepository("StfalconBlogBundle:Post")->findOneBy(array('slug' => 'post-about-php'));

        // delete post
        $crawler = $client->request('POST', $this->getUrl('admin_bundle_blog_post_delete', array('id' => $post->getId())), array('_method' => 'DELETE'));

        // check if post was removed from DB
        $em->detach($post);
        $postRemoved = $em->getRepository("StfalconBlogBundle:Post")->findOneBy(array('slug' => 'post-about-php'));
        $this->assertNull($postRemoved);
    }

}