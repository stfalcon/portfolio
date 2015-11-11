<?php

namespace Stfalcon\Bundle\PortfolioBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;

/**
 * UserWithPositionRepository class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class UserWithPositionRepository extends EntityRepository
{
    /**
     * Find by project
     *
     * @param Project $project Project
     *
     * @return array
     */
    public function findByProject($project)
    {
        $qb = $this->createQueryBuilder('uwp');

        return $qb->where($qb->expr()->eq('uwp.project', $project->getId()))
                  ->getQuery()
                  ->getResult();
    }
}
