<?php

namespace Application\Bundle\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController as Exception;

/**
 * Custom ExceptionController to make translatable 404 error page
 */
class ExceptionController extends Exception
{
    protected $twig;
    protected $debug;
    protected $locales;

    /**
     * @param \Twig_Environment $twig
     * @param                   $debug
     * @param array             $locales
     */
    public function __construct(\Twig_Environment $twig, $debug, $locales)
    {
        $this->twig = $twig;
        $this->debug = $debug;
        $this->locales = $locales;
    }

    /**
     * Converts an Exception to a Response.
     *
     * @param Request              $request   The request
     * @param FlattenException     $exception A FlattenException instance
     * @param DebugLoggerInterface $logger    A DebugLoggerInterface instance
     * @param string               $_format   The format to use for rendering (html, xml, ...)
     *
     * @return Response
     *
     * @throws \InvalidArgumentException When the exception template does not exist
     */
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null, $_format = 'html')
    {
        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));

        $code = $exception->getStatusCode();

        $pathInfo = explode('/', $request->getPathInfo());
        if (isset($pathInfo[1]) && in_array($pathInfo[1], $this->locales)) {
            $request->setLocale($pathInfo[1]);
        }

        return new Response($this->twig->render(
            $this->findTemplate($request, $_format, $code, $this->debug),
            array(
                'status_code'    => $code,
                'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception'      => $exception,
                'logger'         => $logger,
                'currentContent' => $currentContent,
            )
        ));
    }
}
