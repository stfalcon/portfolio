<?php

namespace Application\Bundle\DefaultBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\I18nRoutingBundle\Router\I18nRouter;
use Sonata\SeoBundle\Seo\SeoPage;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * SeoAlternateService class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class SeoAlternateService
{
    /**
     * Constructor
     *
     * @param EntityManager $em      Entity manager
     * @param I18nRouter    $router  Router
     * @param array         $locales Locales
     */
    public function __construct(EntityManager $em, I18nRouter $router, $locales)
    {
        $this->em      = $em;
        $this->router  = $router;
        $this->locales = $locales;
    }

    /**
     * Add alternate for posts
     *
     * @param Post    $post    Post
     * @param SeoPage $seoPage Seo page
     * @param Request $request Request
     */
    public function addAlternateForPost(Post $post, SeoPage $seoPage, Request $request)
    {
        $postRepository = $this->em->getRepository('StfalconBlogBundle:Post');

        $currentLocale = $request->getLocale();
        $route         = $request->get('_route');

        $alternates = [];
        foreach ($this->locales as $locale) {
            if ($locale === $currentLocale) {
                continue;
            }

            $translation = $postRepository->findPostBySlugInLocale($post->getSlug(), $locale);

            if (null !== $translation) {
                $params = [
                    'slug'    => $post->getSlug(),
                    '_locale' => $locale,
                ];

                $alternates[$this->router->generate($route, $params, true)] = $locale;
            }
        }

        $seoPage->setLangAlternates($alternates);
    }

    /**
     * Add alternate for categorys
     *
     * @param Category $category Category
     * @param SeoPage  $seoPage  Seo page
     * @param Request  $request  Request
     */
    public function addAlternateForCategory(Category $category, SeoPage $seoPage, Request $request)
    {
        $categoryRepository = $this->em->getRepository('StfalconPortfolioBundle:Category');

        $currentLocale = $request->getLocale();
        $route         = $request->get('_route');

        $alternates = [];
        foreach ($this->locales as $locale) {
            if ($locale === $currentLocale) {
                continue;
            }

            $translation = $categoryRepository->findCategoryBySlugAndLocale($category->getSlug(), $locale);

            if (null !== $translation) {
                $params = [
                    'slug'    => $category->getSlug(),
                    '_locale' => $locale,
                ];

                $alternates[$this->router->generate($route, $params, true)] = $locale;
            }
        }

        $seoPage->setLangAlternates($alternates);
    }
}
