<?php

namespace Application\Bundle\DefaultBundle\Service\SeoAlternate;

use Doctrine\ORM\EntityManager;
use JMS\I18nRoutingBundle\Router\I18nRouter;
use Sonata\SeoBundle\Seo\SeoPage;
use Symfony\Component\HttpFoundation\Request;

/**
 * AbstractSeoAlternateService class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
abstract class AbstractSeoAlternateService
{
    /**
     * @var EntityManager $em Entity manager
     */
    protected $em;

    /**
     * @var I18nRouter $router Router
     */
    private $router;

    /**
     * @var array $locales Locales
     */
    private $locales;

    /**
     * Constructor
     *
     * @param EntityManager $em      Entity manager
     * @param I18nRouter    $router  Router
     * @param array         $locales Locales
     */
    public function __construct(EntityManager $em, I18nRouter $router, $locales)
    {
        $this->em      = $em;
        $this->router  = $router;
        $this->locales = $locales;
    }

    /**
     * Get translation
     *
     * @param string $identifier Identifier
     * @param string $locale     Locale
     *
     * @return Object|null
     */
    abstract public function getTranslation($identifier, $locale);

    /**
     * Get identifier
     *
     * @param Object $entity Entity
     *
     * @return string
     */
    abstract public function getIdentifier($entity);

    /**
     * Get route params
     *
     * @param Object $entity Entity
     *
     * @return string
     */
    abstract public function getRouteParams($entity);

    /**
     * Add alternate for entity
     *
     * @param Object  $entity  Object
     * @param SeoPage $seoPage Seo page
     * @param Request $request Request
     */
    public function execute($entity, SeoPage $seoPage, Request $request)
    {
        $currentLocale = $request->getLocale();
        $route         = $request->get('_route');

        $alternates = [];
        foreach ($this->locales as $locale) {
            if ($locale === $currentLocale) {
                continue;
            }

            $translation = $this->getTranslation($this->getIdentifier($entity), $locale);

            if (null !== $translation) {
                $params = [
                    '_locale' => $locale,
                ];

                $url = $this->router->generate($route, array_merge($params, $this->getRouteParams($entity)), true);

                $alternates[$url] = $locale;
            }
        }

        $seoPage->setLangAlternates($alternates);
    }
}
