<?php

namespace Application\Bundle\UserBundle\Repository;

use Application\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

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
     * Find user by username and locale
     *
     * @param string $username Username
     * @param string $locale   Locale
     *
     * @return User|null
     */
    public function findUserByUsernameAndLocale($username, $locale)
    {
        $qb = $this->createQueryBuilder('u');

        $qb->andWhere($qb->expr()->eq('u.username', ':username'))
           ->setParameter('username', $username)
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
            $qb->innerJoin('u.translations', 'tr')
               ->andWhere($qb->expr()->eq('tr.locale', ':locale'))
               ->setParameter('locale', $locale)
               ->andWhere($qb->expr()->isNotNull('tr.content'))
               ->addGroupBy('u.id');
        }
    }
}
