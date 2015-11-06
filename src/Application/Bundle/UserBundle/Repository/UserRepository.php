<?php

namespace Application\Bundle\UserBundle\Repository;

use Application\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * User Repository
 */
class UserRepository extends EntityRepository
{

    /**
     * Get all active users
     *
     * @return User[]
     */
    public function findAllActiveUsers()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.enabled = :enable')
            ->setParameter('enable', true)
            ->andWhere('u.locked = :locked')
            ->setParameter('locked', false)
            ->orderBy('u.ordering', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find user by slug
     *
     * @param string $slug Slug
     *
     * @return User
     *
     * @throws NonUniqueResultException
     */
    public function findUserBySlug($slug)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->where($qb->expr()->eq('u.slug', ':slug'))
                  ->setParameter('slug', $slug)
                  ->getQuery()
                  ->getOneOrNullResult();
    }
}
