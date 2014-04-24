<?php

namespace Stfalcon\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;

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
     * @return Query
     */
    public function getAllPublishedPostsAsQuery()
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.published = 1')
            ->orderBy('p.created', 'DESC');

        return $qb->getQuery();
    }

    /**
     * Get all posts
     *
     * @return array
     */
    public function getAllPublishedPosts()
    {
        return $this->getAllPublishedPostsAsQuery()->getResult();
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
        $query = $this->getAllPublishedPostsAsQuery();

        if ((int) $count) {
            $query->setMaxResults($count);
        }

        return $query->getResult();
    }

    /**
     * @param Tag $tag
     *
     * @return Query
     */
    public function findPostsByTagAsQuery($tag)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb->leftJoin('p.tags', 't')
            ->where('t.id = :tagId')
            ->andWhere('p.published = 1')
            ->setParameter('tagId', $tag->getId())
            ->getQuery();
    }

}