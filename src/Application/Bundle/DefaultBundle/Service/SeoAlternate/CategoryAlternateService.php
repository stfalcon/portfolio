<?php

namespace Application\Bundle\DefaultBundle\Service\SeoAlternate;

use Stfalcon\Bundle\PortfolioBundle\Entity\Category;

/**
 * CategoryAlternateService class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class CategoryAlternateService extends AbstractSeoAlternateService
{
    /**
     * Get identifier
     *
     * @param Category $category Category
     *
     * @return string
     */
    public function getIdentifier($category)
    {
        return $category->getSlug();
    }

    /**
     * Get translation
     *
     * @param string $slug   Slug
     * @param string $locale Locale
     *
     * @return Category|null
     */
    public function getTranslation($slug, $locale)
    {
        return $this->em->getRepository('StfalconPortfolioBundle:Category')
                        ->findCategoryBySlugAndLocale($slug, $locale);
    }
}
