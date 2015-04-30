<?php
namespace Application\Bundle\DefaultBundle\Service;

use Doctrine\ORM\EntityManager;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class SitemapService
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $webRoot;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     * @param Router        $router
     * @param string        $webRoot
     */
    public function __construct(EntityManager $entityManager, Router $router, $webRoot)
    {
        $this->router = $router;
        $this->webRoot = $webRoot;
        $this->entityManager = $entityManager;
    }

    /**
     * Generate sitimap.xml
     */
    public function generateSitemap()
    {
        $xmlSitemap = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" />');

        $this->addPortfolioItems($xmlSitemap);
        $this->addBlogItems($xmlSitemap);
        $this->addBlogTags($xmlSitemap);

        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('team', ['_locale' => 'ru'], true),
            null,
            'monthly',
            0.3
        );

        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('team', ['_locale' => 'en'], true),
            null,
            'monthly',
            0.3
        );

        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('contacts', ['_locale' => 'ru'], true)
        );

        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('contacts', ['_locale' => 'en'], true)
        );

        $xmlSitemap->saveXML($this->webRoot . DIRECTORY_SEPARATOR . 'sitemap.xml');
    }

    /**
     * @param \SimpleXMLElement $xmlSitemap
     */
    private function addBlogTags(\SimpleXMLElement $xmlSitemap)
    {
        $blogTagsRepository = $this->entityManager->getRepository('StfalconBlogBundle:Tag');
        $blogTags = $blogTagsRepository->findAll();

        /** @var Tag $tag */
        foreach ($blogTags as $tag) {
            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('blog_tag_view', ['text' => $tag->getText(), '_locale' => 'ru'], true)
            );

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('blog_tag_view', ['text' => $tag->getText(), '_locale' => 'en'], true)
            );
        }
    }

    /**
     * @param \SimpleXMLElement $xmlSitemap
     */
    private function addPortfolioItems(\SimpleXMLElement $xmlSitemap)
    {
        $portfolioCategoriesRepository = $this->entityManager->getRepository('StfalconPortfolioBundle:Category');
        $portfolioCategories = $portfolioCategoriesRepository->findAll();

        foreach ($portfolioCategories as $category) {
            $lastUpdateDate = null;

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_categories_list', ['slug' => $category->getSlug(), '_locale' => 'ru'], true),
                $category->getUpdatedAt()
            );

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_categories_list', ['slug' => $category->getSlug(), '_locale' => 'en'], true)
            );

            $categoryUpdatedAt = ($category->getProjects()->count() > 0)?
                $category->getProjects()->first()->getUpdated():
                $category->getUpdatedAt();

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_category_view', ['slug' => $category->getSlug(), '_locale' => 'ru'], true),
                $categoryUpdatedAt
            );

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_category_view', ['slug' => $category->getSlug(), '_locale' => 'en'], true),
                $categoryUpdatedAt
            );

            /** @var Project $project */
            foreach ($category->getProjects() as $project) {

                $this->addUrlElement(
                    $xmlSitemap,
                    $this->router->generate(
                        'portfolio_project_view',
                        ['categorySlug' => $category->getSlug(), 'projectSlug' => $project->getSlug(), '_locale' => 'ru'],
                        true),
                    $project->getUpdated(),
                    'monthly',
                    0.8
                );

                $this->addUrlElement(
                    $xmlSitemap,
                    $this->router->generate(
                        'portfolio_project_view',
                        ['categorySlug' => $category->getSlug(), 'projectSlug' => $project->getSlug(), '_locale' => 'en'],
                        true),
                    $project->getUpdated(),
                    'monthly',
                    0.8
                );

                if ($lastUpdateDate < $project->getUpdated()) {
                    $lastUpdateDate = $project->getUpdated();
                }
            }

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_all_projects', ['_locale' => 'ru'], true),
                $lastUpdateDate
            );

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_all_projects', ['_locale' => 'en'], true),
                $lastUpdateDate
            );
        }

    }

    private function addBlogItems(\SimpleXMLElement $xmlSitemap)
    {
        $blogPostsRepository = $this->entityManager->getRepository('StfalconBlogBundle:Post');
        $blogPosts = $blogPostsRepository->getAllPublishedPosts('ru');

        /** @var Post $lastPost */
        $lastPost = reset($blogPosts);
        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('blog', ['_locale' => 'ru'], true),
            $lastPost->getCreated()
        );

        /** @var Post $post */
        foreach ($blogPosts as $post) {
            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('blog_post_view', ['slug' => $post->getSlug(), '_locale' => 'ru'], true),
                $post->getCreated(),
                'monthly',
                0.8
            );


        }

        $blogPosts = $blogPostsRepository->getAllPublishedPosts('en');

        /** @var Post $lastPost */
        $lastPost = reset($blogPosts);
        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('blog', ['_locale' => 'en'], true),
            $lastPost->getCreated()
        );

        /** @var Post $post */
        foreach ($blogPosts as $post) {
            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('blog_post_view', ['slug' => $post->getSlug(), '_locale' => 'en'], true),
                $post->getCreated(),
                'monthly',
                0.8
            );
        }

    }

    /**
     * @param \SimpleXMLElement $xmlSitemap
     * @param  string           $url
     * @param \DateTime         $lastMod
     * @param string            $changeFrequency
     * @param float             $priority
     */
    private function addUrlElement(
        \SimpleXMLElement $xmlSitemap,
        $url,
        \DateTime $lastMod = null,
        $changeFrequency = 'monthly',
        $priority = 0.5
    )
    {
        $urlXml = $xmlSitemap->addChild('url');
        $urlXml->addChild('loc', $url);

        if (!is_null($lastMod)) {
            $urlXml->addChild('lastmod', $lastMod->format('Y-m-d\TH:i:sP'));
        }

        $urlXml->addChild('changefreq', $changeFrequency);
        $urlXml->addChild('priority', $priority);
    }
}