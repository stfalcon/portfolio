<?php

namespace Application\Bundle\DefaultBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DotRequestCheckListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $host = $request->getSchemeAndHttpHost();
        if ('.' === substr($host, -1)) {
            $host = substr($host, 0, -1);
            $params = $request->query->all();
            $event->setResponse(new RedirectResponse($host.$request->getRequestUri() . ($params ? '?' . http_build_query($params) : ''), 301));
        }
    }
}