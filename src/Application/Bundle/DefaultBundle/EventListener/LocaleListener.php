<?php
namespace Application\Bundle\DefaultBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Initializes the locale based on the current request.
 */
class LocaleListener
{
    /**
     * @var array
     */
    private $locales;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     * @param array  $locales
     */
    public function __construct(Router $router, array $locales)
    {
        $this->router = $router;
        $this->locales = $locales;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
        $request = $event->getRequest();
        if ($request->hasPreviousSession() || $request->cookies->has('hl')) {
            return;
        }

        $locale = $request->getPreferredLanguage(array_reverse($this->locales));
        $routeName = $request->attributes->get('_route');
        $routeParams = array_replace($request->attributes->get('_route_params'), ['_locale' => $locale]);

        $event->setResponse(new RedirectResponse($this->router->generate($routeName, $routeParams)));
    }
}
