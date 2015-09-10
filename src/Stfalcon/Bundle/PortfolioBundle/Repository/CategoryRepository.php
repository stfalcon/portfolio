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

}