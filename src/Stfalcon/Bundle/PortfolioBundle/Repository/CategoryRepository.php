<?php

namespace Stfalcon\Bundle\PortfolioBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;

/**
 * Category Repository
 */
class CategoryRepository extends EntityRepository
{

    /**
     * Get all categories
     *
     * @return array
     */
    public function getAllCategories()
    {
        return $this->createQueryBuilder('c')
                    ->orderBy('c.ordernum', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Get all categories
     *
     * @return array
     */
    public function getServicesCategories()
    {
        return $this->createQueryBuilder('c')
                    ->where('c.showInServices = 1')
                    ->orderBy('c.ordernum', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Find category by slug and locale
     *
     * @param string $slug   Slug
     * @param string $locale Locale
     *
     * @return Category|null
     */
    public function findCategoryBySlugAndLocale($slug, $locale)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->andWhere($qb->expr()->eq('c.showInServices', true))
           ->andWhere($qb->expr()->eq('c.slug', ':slug'))
           ->orderBy('c.ordernum', 'ASC')
           ->setParameter('slug', $slug)
           ->setMaxResults(1);

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery()
                  ->getOneOrNullResult();
    }

    /**
     * Add locale filter
     *
     * @param string       $locale Locale
     * @param QueryBuilder $qb     Query builder
     */
    private function addLocaleFilter($locale, QueryBuilder $qb)
    {
        if ($locale !== 'ru') {
            $qb->innerJoin('c.translations', 'tr')
               ->andWhere($qb->expr()->eq('tr.locale', ':locale'))
               ->setParameter('locale', $locale)
               ->andWhere($qb->expr()->isNotNull('tr.content'))
               ->addGroupBy('c.id');
        }
    }
}
