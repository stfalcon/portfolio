<?php

namespace Application\Bundle\DefaultBundle\EventListener;

use Application\Bundle\DefaultBundle\Service\GeoIpService;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class LandingLocaleListener
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
     * @param GeoIpService $geoIpService
     * @param Router       $router
     * @param array        $locales List of available locales
     */
    public function __construct(GeoIpService $geoIpService, Router $router, array $locales)
    {
        $this->locales = $locales;
        $this->geoIpService = $geoIpService;
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            // don't do anything if it's not the master request
            return;
        }

        if ($request->getHost() == 'stfalcon.de') {
            return;
        }

        if (!in_array($request->attributes->get('_route'), ['page_promotion', 'page_promotion_new'])) {
            return;
        }

        if ($request->query->has('_check')) {
            return;
        }

        $locale = $this->geoIpService->getLocaleByIp($request->getClientIp());

        if ($request->getLocale() == $locale) {
            return;
        }

        $currentRouteParams = array_replace($request->attributes->get('_route_params'), ['_locale' => $locale]);
        $redirectUrl = $this->router->generate($request->attributes->get('_route'), $currentRouteParams);
        $event->setResponse(new RedirectResponse($redirectUrl, 302));
    }
}