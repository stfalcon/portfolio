<?php

namespace Stfalcon\Bundle\PortfolioBundle\Repository;

use Application\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPosition;

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

    /**
     * Find by user and project
     *
     * @param User    $user
     * @param Project $project
     *
     * @return null|UserWithPosition
     */
    public function findByUserAndProject($user, $project)
    {
        $qb = $this->createQueryBuilder('uwp');

        return $qb->where($qb->expr()->eq('uwp.project', ':project'))
                  ->andWhere($qb->expr()->eq('uwp.user', ':user'))
                  ->setParameters([
                      'project' => $project,
                      'user'    => $user,
                  ])
                  ->getQuery()
                  ->getOneOrNullResult();
    }
}
