<?php

namespace Application\Bundle\DefaultBundle\EventListener;

use Application\Bundle\DefaultBundle\Service\GeoIpService;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class LocaleListener uses for detect user locale by IP and change site locale
 */
class LocaleListener
{
    /**
     * @var array
     */
    protected $locales;

    /**
     * @var GeoIpService
     */
    private $geoIpService;

    /**
     * @var Router
     */
    private $router;
    /**
     * @var string
     */
    private $localeCookieName;
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @param GeoIpService $geoIpService
     * @param Router       $router
     * @param array        $locales List of available locales
     * @param string       $localeCookieName
     * @param Kernel       $kernel
     */
    public function __construct(GeoIpService $geoIpService, Router $router, array $locales, $localeCookieName, Kernel $kernel)
    {
        $this->locales = $locales;
        $this->geoIpService = $geoIpService;
        $this->router = $router;
        $this->localeCookieName = $localeCookieName;
        $this->kernel = $kernel;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->kernel->getEnvironment() == 'test' || HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            // don't do anything if it's not the master request
            return;
        }

        $request = $event->getRequest();

        if ($request->attributes->get('_route') == 'promotion_order' || $request->getHost() == 'stfalcon.de') {
            return;
        }

        if ($request->query->has('_check')) {
            return;
        }

        $response = new RedirectResponse('/');

        $locale = $this->geoIpService->getLocaleByIp($request->getClientIp());

        if (!in_array($request->attributes->get('_route'), ['page_promotion', 'page_promotion_new'])) {
            $session = $request->getSession();

            if ($cookieLocale = $request->cookies->get($this->localeCookieName)) {
                if ($request->getLocale() == $cookieLocale) {
                    $locale = $cookieLocale;
                } else {
                    $locale = $request->getLocale();
                    $session->set('_locale', $locale);
                }
            } else {
                $session->set('_locale', $locale);
            }

            if ($locale == 'de') {
                $locale = 'en';
            }
        }

        if ($request->getLocale() == $locale) {
            return;
        }

        $currentRouteParams = array_replace($request->attributes->get('_route_params'), ['_locale' => $locale]);
        $redirectUrl = $this->router->generate($request->attributes->get('_route'), $currentRouteParams);

        $response->setTargetUrl($redirectUrl);

        $event->setResponse($response);
    }
}