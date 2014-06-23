<?php

namespace Application\Bundle\DefaultBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApplicationExceptionListener
{
    protected $locales;

    /**
     * @param array $locales List of available locales
     */
    public function __construct($locales)
    {
        $this->locales = $locales;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $pathInfo = explode('/', $event->getRequest()->getPathInfo());
        if (isset($pathInfo[1]) && in_array($pathInfo[1], $this->locales)) {
            $event->getRequest()->setLocale($pathInfo[1]);
        }
    }
}