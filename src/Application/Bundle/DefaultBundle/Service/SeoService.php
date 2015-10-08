<?php

namespace Application\Bundle\DefaultBundle\Service;

use JMS\I18nRoutingBundle\Router\I18nRouter;
use Sonata\SeoBundle\Seo\SeoPage;

/**
 * SeoService
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class SeoService
{
    /**
     * @var SeoPage $sonataSeoPage SeoPage
     */
    private $sonataSeoPage;

    /**
     * @var I18nRouter $router I18nRouter
     */
    private $router;

    /**
     * Constructor
     *
     * @param SeoPage    $sonataSeoPage SeoPage
     * @param I18nRouter $router        I18nRouter
     */
    public function __construct(SeoPage $sonataSeoPage, I18nRouter $router)
    {
        $this->sonataSeoPage = $sonataSeoPage;
        $this->router        = $router;
    }

    /**
     * Set open graph seo data
     *
     * @param string $routeName Route name
     * @param string $title     Title
     * @param string $type      Open graph page type
     */
    public function setOpenGraphSeoData($routeName, $title = '', $type = 'website')
    {
        $this->sonataSeoPage
            ->addMeta('property', 'og:image', 'http://stfalcon.com/img/facebook_cover.jpg');

        if (!empty($routeName)) {
            $this->sonataSeoPage
                ->addMeta('property', 'og:type', $type);
        }

        if (!empty($routeName)) {
            $canonicalUrl = $this->router->generate($routeName, [], true);

            $this->sonataSeoPage
                ->addMeta('property', 'og:url', $canonicalUrl)
                ->setLinkCanonical($canonicalUrl);
        }

        if (!empty($title)) {
            $this->sonataSeoPage
                ->addMeta('name', 'title', $title)
                ->addMeta('property', 'og:title', $title);
        }
    }
}
