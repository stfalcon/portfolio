<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;

/**
 * Widgets controller
 *
 */
class WidgetsController extends Controller
{
    /**
     * @param Request $request
     *
     * @Template("ApplicationDefaultBundle:Widgets:_language_switcher.html.twig")
     * @return array
     */
    public function languageSwitcherAction($request)
    {
        $locales = array(
//            'uk' => array($this->localizeRoute($request, 'uk'), 'uk'),
            'ru' => array(
                'link' => $this->localizeRoute($request, 'ru'),
                'lang' => 'ру'
            ),
            'en' => array(
                'link' => $this->localizeRoute($request, 'en'),
                'lang' => 'en'
            )
        );

        return array('locales' => $locales);
    }

    /**
     * Localize current route
     *
     * @param Request $request
     * @param string  $locale
     *
     * @return string
     */
    protected function localizeRoute($request, $locale)
    {
        $attributes = array_merge($request->query->all(), $request->attributes->get('_route_params'));

        // Set/override locale
        $attributes['_locale'] = $locale;

        $route = $request->attributes->get('_route');
        if (is_null($route)) {
            $route = 'homepage';
        }

        return $this->generateUrl($route, $attributes);
    }
}