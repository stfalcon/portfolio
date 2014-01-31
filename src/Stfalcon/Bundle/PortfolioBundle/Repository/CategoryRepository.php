<?php

namespace Stfalcon\Bundle\PortfolioBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
        $query = $this->getEntityManager()->createQuery('
            SELECT
                c
            FROM
                StfalconPortfolioBundle:Category c
            ORDER BY
                c.ordernum');

        return $query->getResult();
    }

}