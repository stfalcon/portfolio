<?php

namespace Application\Bundle\DefaultBundle\EventListener;

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
     * @param I18nRouter $router  I18nRouter
     * @param SeoPage    $seoPage SeoPage
     */
    public function __construct(I18nRouter $router, SeoPage $seoPage)
    {
        $this->router = $router;
        $this->seoPage = $seoPage;
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

            foreach ($langs as $lang) {
                $attributes['_locale'] = $lang;

                $this->seoPage->addLangAlternate(
                    $this->router->generate($route, $attributes, true),
                    $lang
                );
            }
        }
    }
}
