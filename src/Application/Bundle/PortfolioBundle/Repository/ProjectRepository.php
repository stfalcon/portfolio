<?php

namespace Application\Bundle\PortfolioBundle\Repository;

use Stfalcon\Bundle\PortfolioBundle\Repository\ProjectRepository as BaseRepository;

/**
 * Project Repository
 */
class ProjectRepository extends BaseRepository
{
    public function findAllProjectsOrderingByDate()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
