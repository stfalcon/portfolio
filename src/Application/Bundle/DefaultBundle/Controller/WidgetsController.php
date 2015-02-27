<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            'en' => array(
                'link' => $this->localizeRoute($request, 'en'),
                'lang' => 'EN'
            ),
            'de' => array(
                'link' => $this->localizeRoute($request, 'de'),
                'lang' => 'DE'
            ),
            'ru' => array(
                'link' => $this->localizeRoute($request, 'ru'),
                'lang' => 'RU'
            ),
        );

        return array('locales' => $locales);
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @Template("ApplicationDefaultBundle:Widgets:_subscribe_form.html.twig")
     * @Route(
     *      "/subscribe/{category}",
     *      requirements={"category" = "blog"},
     *      name="post_subscribe"
     * )
     */
    public function subscribeWidgetAction(Request $request, $category)
    {
        $form = $this->createForm('subscribe', []);

        if ($request->isMethod('post') && $request->isXmlHttpRequest()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $mc = $this->get('hype_mailchimp');
                $mc->getList()->subscribe($form->get('email')->getData(), 'html', false);

                return new JsonResponse([
                    'success' => true
                ]);
            }

            return new JsonResponse([
                'success' => false,
                'view' => $this->renderView('ApplicationDefaultBundle:Widgets:_subscribe_form.html.twig', [
                    'form' => $form->createView(),
                    'category' => $category
                ])
            ]);
        }

        return [
            'form' => $form->createView(),
            'category' => $category
        ];
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
        $routeParams = $request->attributes->get('_route_params');
        $attributes = $request->query->all();
        if (!is_null($routeParams)) {
            $attributes = array_merge($attributes, $routeParams);
        }

        // Set/override locale
        $attributes['_locale'] = $locale;

        $route = $request->attributes->get('_route');
        if (is_null($route)) {
            $route = 'homepage';
        }

        return $this->generateUrl($route, $attributes);
    }
}