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
            'ru' => array($this->localizeRoute($request, 'ru'), 'ру'),
            'en' => array($this->localizeRoute($request, 'en'), 'en')
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

        return $this->generateUrl($request->attributes->get('_route'), $attributes);
    }
}