<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed;

/**
 * PostController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostController extends AbstractController
{

    private function _getRequestArrayWithDisqusShortname($array)
    {
        $config = $this->container->getParameter('stfalcon_blog.config');

        return array_merge(
            $array,
            array('disqus_shortname' => $config['disqus_shortname'])
        );
    }

    /**
     * List of posts for admin
     *
     * @Route("/blog/{title}/{page}", name="blog",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     *
     * @param int $page Page number
     *
     * @return array
     */
    public function indexAction($page)
    {
        $allPosts = $this->get('doctrine')->getManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPosts();
        $posts= $this->get('knp_paginator')->paginate($allPosts, $page, 10);

        return $this->_getRequestArrayWithDisqusShortname(array(
            'posts' => $posts
        ));
    }

    /**
     * View post
     *
     * @Route("/blog/post/{slug}", name="blog_post_view")
     * @Template()
     *
     * @param Post $post
     *
     * @return array
     */
    public function viewAction(Post $post)
    {
        return $this->_getRequestArrayWithDisqusShortname(array(
            'post' => $post
        ));
    }

    /**
     * RSS feed
     *
     * @Route("/blog/rss", name="blog_rss")
     *
     * @return Response
     */
    public function rssAction()
    {
        $feed = new Feed();

        $config = $this->container->getParameter('stfalcon_blog.config');

        $feed->setTitle($config['rss']['title']);
        $feed->setDescription($config['rss']['description']);
        $feed->setLink($this->generateUrl('blog_rss', array(), true));

        $posts = $this->get('doctrine')->getManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPosts();
        foreach ($posts as $post) {
            $entry = new Entry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($this->generateUrl('blog_post_view', array('slug' => $post->getSlug()), true));

            $feed->addEntry($entry);
        }

        return new Response($feed->export('rss'));
    }

    /**
     * Show last blog posts
     *
     * @param int $count A count of posts
     *
     * @return array()
     *
     * @Template()
     */
    public function lastAction($count = 1)
    {
        $posts = $this->get('doctrine')->getManager()
                ->getRepository("StfalconBlogBundle:Post")->getLastPosts($count);

        return array('posts' => $posts);
    }
}