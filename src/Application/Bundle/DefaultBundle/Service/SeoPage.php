<?php

namespace Application\Bundle\DefaultBundle\Service;

use Sonata\SeoBundle\Seo\SeoPage as DefaultSeoPage;
use Symfony\Component\HttpFoundation\Request;

class SeoPage extends DefaultSeoPage
{
    private $router;

    public function setRouter($router) {
        $this->router = $router;
    }

    public function getRouter() {
        return $this->router;
    }

    /**
     * @param Request $request
     * @param array $langs
     */
    public function generateLangAlternates(Request $request, $langs = ['en', 'ru']) {

        $route = $request->attributes->get('_route');

        $routeParams = $request->attributes->get('_route_params');
        $attributes = $request->query->all();

        if (!is_null($routeParams)) {
            $attributes = array_merge($attributes, $routeParams);
        }

        foreach($langs as $lang) {
            $attributes['_locale']=$lang;

            $this->addLangAlternate(
                $this->getRouter()->generate($route, $attributes, true),
                $lang
            );
        }
    }
}
