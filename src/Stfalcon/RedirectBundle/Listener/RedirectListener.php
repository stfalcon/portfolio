<?php
namespace Stfalcon\RedirectBundle\Listener;

use Stfalcon\RedirectBundle\Service\RedirectService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Class RedirectListener
 */
class RedirectListener
{
    /**
     * @var RedirectService
     */
    private $redirectService;

    /**
     * @param RedirectService $redirectService
     */
    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelExceptionRequest(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof NotFoundHttpException) {
            return;
        }

        $url = $event->getRequest()->getRequestUri();

        $redirect = $this->redirectService->getRedirect($url);
        if ($redirect) {
            $response = new RedirectResponse($redirect->getTarget(), $redirect->getCode());
            $event->setResponse($response);
        }
    }
}