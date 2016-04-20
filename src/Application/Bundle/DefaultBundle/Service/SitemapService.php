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
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Constructor
     *
     * @param EntityManager     $entityManager Entity manager
     * @param Router            $router        Router
     * @param \Twig_Environment $twig          Twig
     * @param string            $webRoot       Web root
     */
    public function __construct(EntityManager $entityManager, Router $router, \Twig_Environment $twig, $webRoot)
    {
        $this->entityManager = $entityManager;
        $this->router        = $router;
        $this->twig          = $twig;
        $this->webRoot       = $webRoot;
    }

    /**
     * @param string $lang
     *
     * @return \SimpleXMLElement
     */
    public function getXMLSitemapByLocale($lang) {

        $xmlSitemap = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" />');

        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('homepage', ['_locale' => $lang], true),
            null,
            'weekly',
            1
        );

        $this->addPortfolioItems($xmlSitemap, $lang);
        $this->addBlogItems($xmlSitemap, $lang);
        $this->addBlogTags($xmlSitemap, $lang);

        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('team', ['_locale' => $lang], true),
            null,
            'weekly'
        );

        $this->addUrlElement(
            $xmlSitemap,
            $this->router->generate('contacts', ['_locale' => $lang], true)
        );

        return $xmlSitemap;
    }

    /**
     * Generate sitimap.xml
     */
    public function generateSitemap()
    {
        $ruXmlSitemap = $this->getXMLSitemapByLocale('ru');
        $ruXmlSitemap->saveXML($this->webRoot.DIRECTORY_SEPARATOR.'sitemap'.DIRECTORY_SEPARATOR.'ru.xml');

        $enXmlSitemap = $this->getXMLSitemapByLocale('en');
        $enXmlSitemap->saveXML($this->webRoot.DIRECTORY_SEPARATOR.'sitemap'.DIRECTORY_SEPARATOR.'en.xml');

        $renderedSitemapIndex = $this->twig->render('::sitemap.xml.twig');
        file_put_contents($this->webRoot.'/sitemap.xml', $renderedSitemapIndex);
    }

    /**
     * @param \SimpleXMLElement $xmlSitemap
     */
    private function addBlogTags(\SimpleXMLElement $xmlSitemap, $locale)
    {
        $blogTagsRepository = $this->entityManager->getRepository('StfalconBlogBundle:Tag');
        $blogTags = $blogTagsRepository->findAll();

        /** @var Tag $tag */
        foreach ($blogTags as $tag) {
            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('blog_tag_view', ['text' => $tag->getText(), '_locale' => $locale], true)
            );
        }
    }

    /**
     * @param \SimpleXMLElement $xmlSitemap
     * @param string            $locale
     */
    private function addPortfolioItems(\SimpleXMLElement $xmlSitemap, $locale)
    {
        $portfolioCategoriesRepository = $this->entityManager->getRepository('StfalconPortfolioBundle:Category');
        $portfolioCategories = $portfolioCategoriesRepository->findAll();

        foreach ($portfolioCategories as $category) {
            $lastUpdateDate = null;

            $categoryUpdatedAt = ($category->getProjects()->count() > 0)?
                $category->getProjects()->first()->getUpdated():
                $category->getUpdatedAt();

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_categories_list', ['slug' => $category->getSlug(), '_locale' => $locale], true),
                $categoryUpdatedAt
            );

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_category_view', ['slug' => $category->getSlug(), '_locale' => $locale], true),
                $categoryUpdatedAt
            );

            /** @var Project $project */
            foreach ($category->getProjects() as $project) {

                $this->addUrlElement(
                    $xmlSitemap,
                    $this->router->generate(
                        'portfolio_project_view',
                        ['categorySlug' => $category->getSlug(), 'projectSlug' => $project->getSlug(), '_locale' => $locale],
                        true),
                    $project->getUpdated(),
                    'weekly'
                );

                if ($lastUpdateDate < $project->getUpdated()) {
                    $lastUpdateDate = $project->getUpdated();
                }
            }

            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('portfolio_all_projects', ['_locale' => $locale], true),
                $lastUpdateDate
            );
        }
    }

    /**
     * @param \SimpleXMLElement $xmlSitemap
     * @param string            $locale
     */
    private function addBlogItems(\SimpleXMLElement $xmlSitemap, $locale)
    {
        $blogPostsRepository = $this->entityManager->getRepository('StfalconBlogBundle:Post');
        $blogPosts = $blogPostsRepository->getAllPublishedPosts($locale);

        if (!empty($blogPosts)) {
            /** @var Post $lastPost */
            $lastPost = reset($blogPosts);
            $this->addUrlElement(
                $xmlSitemap,
                $this->router->generate('blog', ['_locale' => $locale], true),
                $lastPost->getCreated()
            );

            /** @var Post $post */
            foreach ($blogPosts as $post) {
                $this->addUrlElement(
                    $xmlSitemap,
                    $this->router->generate('blog_post_view', ['slug' => $post->getSlug(), '_locale' => $locale], true),
                    $post->getCreated(),
                    'weekly'
                );
            }
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
        $changeFrequency = 'weekly',
        $priority = 0.8
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
