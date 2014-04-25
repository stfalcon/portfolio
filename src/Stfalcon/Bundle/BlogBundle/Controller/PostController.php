<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed;

/**
 * PostController
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostController extends AbstractController
{
    /**
     * List of posts for admin
     *
     * @param int $page Page number
     *
     * @return array
     *
     * @Route("/blog/{title}/{page}", name="blog",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     */
    public function indexAction($page)
    {
        $allPostsQuery = $this->get('doctrine')->getManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPublishedPostsAsQuery();
        $posts= $this->get('knp_paginator')->paginate($allPostsQuery, $page, 10);

        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Блог')->setCurrent(true);
        }

        return $this->_getRequestArrayWithDisqusShortname(array(
            'posts' => $posts
        ));
    }

    /**
     * View post
     *
     * @param Post $post
     *
     * @return array
     *
     * @throws NotFoundHttpException
     *
     * @Route("/blog/post/{slug}", name="blog_post_view")
     * @Template()
     */
    public function viewAction(Post $post)
    {
        if (!$post->isPublished()) {
            throw new NotFoundHttpException('Post not found');
        }
        if ($this->has('application_default.menu.breadcrumbs')) {
            $breadcrumbs = $this->get('application_default.menu.breadcrumbs');
            $breadcrumbs->addChild('Блог', array('route' => 'blog'));
            $breadcrumbs->addChild($post->getTitle())->setCurrent(true);
        }

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
                ->getRepository("StfalconBlogBundle:Post")->getAllPublishedPosts();
        foreach ($posts as $post) {
            $entry = new Entry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($this->generateUrl('blog_post_view', array('slug' => $post->getSlug()), true));

            $feed->addEntry($entry);
        }

        $response = new Response($feed->export('rss'));
        $response->headers->add(array('Content-Type' => 'application/xml'));

        return $response;
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

    /**
     * @param array $array
     *
     * @return array
     */
    private function _getRequestArrayWithDisqusShortname($array)
    {
        $config = $this->container->getParameter('stfalcon_blog.config');

        return array_merge(
            $array,
            array('disqus_shortname' => $config['disqus_shortname'])
        );
    }
}