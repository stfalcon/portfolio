<?php

namespace Stfalcon\Bundle\BlogBundle\Controller;

use Application\Bundle\DefaultBundle\Helpers\SeoOpenGraphEnum;
use Application\Bundle\UserBundle\Entity\User;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @param Request $request Request
     * @param int     $page    Page number
     *
     * @return array
     *
     * @Route("/blog/{title}/{page}", name="blog",
     *      requirements={"page"="\d+", "title"="page"},
     *      defaults={"page"="1", "title"="page"})
     * @Template()
     */
    public function indexAction(Request $request, $page)
    {
        $postRepository = $this->getDoctrine()->getRepository('StfalconBlogBundle:Post');

        $postsQuery = $postRepository->getAllPublishedPostsAsQuery($request->getLocale());
        $posts      = $this->get('knp_paginator')->paginate($postsQuery->getResult(), $page, 10);

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        return $this->_getRequestArrayWithDisqusShortname([
            'posts' => $posts,
        ]);
    }

    /**
     * View post
     *
     * @param Request $request
     * @param string  $slug
     *
     * @return array
     *
     * @throws NotFoundHttpException
     *
     * @Route("/blog/post/{slug}", name="blog_post_view")
     * @Template()
     */
    public function viewAction(Request $request, $slug)
    {
        /** @var Post $post */
        $post = $this->getDoctrine()
                     ->getRepository('StfalconBlogBundle:Post')
                     ->findPostBySlugInLocale($slug, $request->getLocale());

        if (!$post) {
            return $this->redirect($this->generateUrl('blog'));
        }

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta('name', 'description', $post->getMetaDescription())
            ->addMeta('name', 'keywords', $post->getMetaKeywords())
            ->addMeta('property', 'og:title', $post->getMetaTitle())
            ->addMeta('property', 'og:url', $this->generateUrl($request->get('_route'), [
                'slug' => $post->getSlug(),
            ], true))
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::ARTICLE)
            ->addMeta('property', 'og:description', $post->getMetaDescription());

        if ($post->getImage()) {
            $seo->addMeta('property', 'og:image', $request->getSchemeAndHttpHost().$post->getImage());
        }

        $this->get('app.default.seo_alternate')->addAlternate($post, $seo, $request);

        return $this->_getRequestArrayWithDisqusShortname([
            'post' => $post,
        ]);
    }

    /**
     * RSS feed
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @Route("/blog/rss", name="blog_rss")
     */
    public function rssAction(Request $request)
    {
        $feed = new Feed();

        $config = $this->container->getParameter('stfalcon_blog.config');

        $feed->setTitle($config['rss']['title']);
        $feed->setDescription($config['rss']['description']);
        $feed->setLink($this->generateUrl('blog_rss', array(), true));

        $posts = $this->get('doctrine')->getManager()
                ->getRepository("StfalconBlogBundle:Post")->getAllPublishedPosts($request->getLocale());
        /** @var Post $post */
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
     * @param string $locale Locale
     * @param int    $count  A count of posts
     *
     * @return array()
     *
     * @Template()
     */
    public function lastAction($locale, $count = 1)
    {
        $posts = $this->get('doctrine')->getManager()
                ->getRepository("StfalconBlogBundle:Post")->getLastPosts($locale, $count);

        return array('posts' => $posts);
    }

    /**
     * Show last blog posts
     *
     * @param string $locale Locale
     * @param int    $count  A count of posts
     *
     * @return array()
     *
     * @Template()
     */
    public function lastHomepageAction($locale, $count = 1)
    {
        $posts = $this->get('doctrine')->getManager()
            ->getRepository("StfalconBlogBundle:Post")->getLastPosts($locale, $count);

        return array('posts' => $posts);
    }

    /**
     * Related posts action
     *
     * @param string $locale Site locale
     * @param Post   $post   Current post
     *
     * @return array
     */
    public function relatedPostsAction($locale, $post)
    {
        $postRepository = $this->getDoctrine()->getManager()->getRepository('StfalconBlogBundle:Post');
        $relatedPosts = $postRepository->findRelatedPostsToCurrentPost($locale, $post);

        return $this->render('@StfalconBlog/Post/relatedPosts.twig', [
            'related_posts' => $relatedPosts,
        ]);
    }

    /**
     * Users post action
     *
     * @param Request $request Request
     * @param User    $user    User
     * @param int     $page    Page number
     *
     * @return Response
     *
     * @Route(
     *      "/blog/{usernameCanonical}/{title}/{page}",
     *      name         = "blog_author",
     *      requirements = {"page" = "\d+", "title" = "page"},
     *      defaults     = {"page" = "1",   "title" = "page"}
     * )
     * @ParamConverter(
     *      "user",
     *      class   = "ApplicationUserBundle:User",
     *      options = {"usernameCanonical" = "usernameCanonical"}
     * )
     */
    public function usersPostAction(Request $request, User $user, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $postRepository = $em->getRepository('StfalconBlogBundle:Post');
        $postsQuery     = $postRepository->getPostsQueryByUser($user, $request->getLocale());

        /** @var SlidingPagination $paginatedPosts */
        $paginatedPosts = $this->get('knp_paginator')->paginate($postsQuery, $page, 10);
        $totalCount     = $paginatedPosts->getTotalItemCount();

        if ((int) $totalCount === 0) {
            return $this->redirect($this->generateUrl('blog', [
                'page'  => 1,
                'title' => 'page',
            ]));
        }

        $seo = $this->get('sonata.seo.page');
        $seo->addMeta(
            'property',
            'og:url',
            $this->generateUrl(
                $request->get('_route'),
                [
                    'usernameCanonical' => $user->getUsernameCanonical(),
                ],
                true
            )
        )
            ->addMeta('property', 'og:type', SeoOpenGraphEnum::WEBSITE);

        $this->get('app.default.seo_alternate')->addAlternate($user, $seo, $request);

        return $this->render('@StfalconBlog/Post/index.html.twig', $this->_getRequestArrayWithDisqusShortname([
            'posts' => $paginatedPosts,
        ]));
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
