<?php

namespace Stfalcon\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostRepository extends EntityRepository
{

    /**
     * Get all posts
     *
     * @return array
     */
    public function getAllPosts()
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.created', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Get last posts
     *
     * @param int $count Max count of returned posts
     *
     * @return array
     */
    public function getLastPosts($count = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.created', 'DESC');

        if ((int) $count) {
            $qb->setMaxResults($count);
        }

        return $qb->getQuery()->getResult();
    }

    public function findPostsByTagAsQuery($tag)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb->leftJoin('p.tags', 't')
            ->where('t.id = :tagId')
            ->setParameter('tagId', $tag->getId())
            ->getQuery();
    }

}