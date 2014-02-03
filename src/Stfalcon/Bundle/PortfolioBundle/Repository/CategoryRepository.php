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
        return $this->createQueryBuilder('c')
            ->orderBy('c.ordernum', 'ASC')
            ->getQuery()
            ->getResult();
    }

}