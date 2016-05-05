<?php

namespace Application\Bundle\DefaultBundle\EventListener;

use Doctrine\ORM\EntityManager;
use JMS\I18nRoutingBundle\Router\I18nRouter;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * SeoListener
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class SeoRequestListener
{
    /**
     * @var I18nRouter $router I18nRouter
     */
    private $router;

    /**
     * @var SeoPage $seoPage SeoPage
     */
    private $seoPage;

    /**
     * @var EntityManager $em Entity manager
     */
    private $em;

    /**
     * Constructor
     *
     * @param I18nRouter    $router  I18nRouter
     * @param SeoPage       $seoPage SeoPage
     * @param EntityManager $em      Entity manager
     */
    public function __construct(I18nRouter $router, SeoPage $seoPage, EntityManager $em)
    {
        $this->router  = $router;
        $this->seoPage = $seoPage;
        $this->em      = $em;
    }

    /**
     * Setting lang seo alternatives
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $langs   = ['en', 'ru'];
        $request = $event->getRequest();

        $route = $request->attributes->get('_route');
        if ($route) {
            $routeParams = $request->attributes->get('_route_params');
            $attributes  = $request->query->all();

            if (!is_null($routeParams)) {
                $attributes = array_merge($attributes, $routeParams);
            }

            if ('blog_post_view' === $route) {
                $this->addBlogLangAlternates($route, $langs, $attributes);
            } else {
                $this->addDefaultLangAlternates($route, $langs, $attributes);
            }
        }
    }

    /**
     * Add blog lang alternates
     *
     * @param string $route      Current route
     * @param array  $langs      Languages
     * @param array  $attributes Route attributes
     */
    private function addBlogLangAlternates($route, $langs, $attributes)
    {
        $slug = isset($attributes['slug']) ? $attributes['slug'] : null;
        $attr = $attributes;

        if (null !== $slug) {
            foreach ($langs as $lang) {
                if ($lang !== $attributes['_locale']) {
                    $post = $this->em->getRepository('StfalconBlogBundle:Post')->findPostBySlugInLocale($slug, $lang);

                    if (null !== $post) {
                        $attr['_locale'] = $lang;

                        $this->seoPage->addLangAlternate(
                            $this->router->generate($route, $attr, true),
                            $lang
                        );
                    }
                }
            }
        } else {
            $this->addDefaultLangAlternates($route, $langs, $attributes);
        }
    }

    /**
     * Add default lang alternates
     *
     * @param string $route      Current route
     * @param array  $langs      Languages
     * @param array  $attributes Route attributes
     */
    private function addDefaultLangAlternates($route, $langs, $attributes)
    {
        $attr = $attributes;

        foreach ($langs as $lang) {
            if ($lang !== $attributes['_locale']) {
                $attr['_locale'] = $lang;

                $this->seoPage->addLangAlternate(
                    $this->router->generate($route, $attr, true),
                    $lang
                );
            }
        }
    }
}
