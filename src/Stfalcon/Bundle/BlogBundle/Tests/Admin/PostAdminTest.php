<?php

namespace StfalconBundle\Bundle\BlogBundle\Tests\Admin;

use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;
use Stfalcon\Bundle\BlogBundle\Repository\TagRepository;
use Symfony\Component\HttpKernel\Client;

/**
 * Test cases for PostAdmin
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostAdminTest extends WebTestCase
{
    /** @var Client */
    protected $client;

    public function __construct()
    {
        $this->client = $this->makeClient();
    }

    public function testEmptyPostsListForAdmin()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
        ));

        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_blog_post_list', array()));

        // check don't display categories
        $this->assertEquals(1, $crawler->filter('div.sonata-ba-list:contains("No result")')->count());
    }

    public function testCreateNewPost()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
        ));

        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_blog_post_create', array()));

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        /** @var TagRepository $tagRepository */
        $tagRepository = $this->getContainer()
                              ->get('doctrine')
                              ->getManager()
                              ->getRepository('StfalconBlogBundle:Tag');

        /** @var Tag $phpTag */
        /** @var Tag $symfonyTag */
        $phpTag     = $tagRepository->findOneBy(['text' => 'php']);
        $symfonyTag = $tagRepository->findOneBy(['text' => 'symfony2']);

        $form[$formId . '[translations][defaultLocale][ru][title]'] = 'Post title';
        $form[$formId . '[translations][defaultLocale][ru][text]'] = 'Post text';
        $form[$formId . '[slug]'] = 'post-slug';
        $form[$formId . '[tags]']->select([$phpTag->getId(), $symfonyTag->getId()]);
        $form[$formId . '[author]'] = 1;
        $this->client->submit($form);

        // check responce
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertFalse($this->client->getResponse()->isRedirect());

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_blog_post_list', array()));
        // check display new category in list
        $this->assertEquals(1, $crawler->filter('table.table tbody tr td:contains("Post title")')->count());
    }

    public function testNotEmptyPostListForAdmin()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));

        $this->doLogin();
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_blog_post_list', array()));

        // check display posts list
        $this->assertEquals(1, $crawler->filter('table.table tbody tr td:contains("My first post")')->count());
    }

    public function testEditPost()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData'));

        $this->doLogin();
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();
        $post = $em->getRepository("StfalconBlogBundle:Post")->findOneBy(array('slug' => 'post-about-php'));

        /** @var TagRepository $tagRepository */
        $tagRepository = $em->getRepository('StfalconBlogBundle:Tag');

        /** @var Tag $phpTag */
        /** @var Tag $symfonyTag */
        $phpTag     = $tagRepository->findOneBy(['text' => 'php']);
        $symfonyTag = $tagRepository->findOneBy(['text' => 'symfony2']);

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_blog_post_edit', array('id' => $post->getId())));

        $form = $crawler->selectButton('btn_update_and_edit')->form();
        $formId = substr($form->getUri(), -14);

        $form[$formId . '[translations][defaultLocale][ru][title]'] = 'New post title';
        $form[$formId . '[translations][defaultLocale][ru][text]'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua..';
        $form[$formId . '[slug]'] = 'new-post-slug';
        $form[$formId . '[tags]']->select([$phpTag->getId(), $symfonyTag->getId()]);
        $this->client->submit($form);

        // check responce
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertFalse($this->client->getResponse()->isRedirect());

        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_blog_post_list', array()));
        $this->assertEquals(1, $crawler->filter('table.table tbody tr td:contains("New post title")')->count());
    }

    public function testDeletePost()
    {
        $this->loadFixtures(array(
            'Application\Bundle\UserBundle\DataFixtures\ORM\LoadUserData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadTagData',
            'Stfalcon\Bundle\BlogBundle\DataFixtures\ORM\LoadPostData',
        ));

        $this->doLogin();
        $em = $this->getContainer()->get('doctrine')->getManager();
        $post = $em->getRepository("StfalconBlogBundle:Post")->findOneBy(array('slug' => 'post-about-php'));

        // delete post
        $crawler = $this->client->request('GET', $this->getUrl('admin_stfalcon_blog_post_delete', array('id' => $post->getId())));
        $form = $crawler->selectButton('Yes, delete')->form();
        $this->client->submit($form);
        // check if post was removed from DB
        $em->detach($post);
        $postRemoved = $em->getRepository("StfalconBlogBundle:Post")->findOneBy(array('slug' => 'post-about-php'));
        $this->assertNull($postRemoved);
    }

    /**
     * Do login with username
     *
     * @param string $username
     */
    private function doLogin($username = 'admin')
    {
        $crawler = $this->client->request('GET', $this->getUrl('fos_user_security_login', array()));
        $form = $crawler->selectButton('_submit')->form(array(
            '_username' => $username,
            '_password' => 'qwerty'
        ));
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirects();
    }
}
