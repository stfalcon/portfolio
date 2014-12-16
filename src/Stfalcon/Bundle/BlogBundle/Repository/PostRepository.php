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
     * @param string $locale
     *
     * @return Query
     */
    public function getAllPublishedPostsAsQuery($locale)
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.published = 1')
            ->orderBy('p.created', 'DESC');
        if ($locale != 'ru') {
            $qb->innerJoin('p.translations', 't')
                ->andWhere('t.locale = :locale')
                ->setParameter('locale', $locale)
                ->andWhere($qb->expr()->isNotNull('t.content'));
        }

        return $qb->getQuery();
    }

    /**
     * Get all posts
     *
     * @param string $locale
     *
     * @return array
     */
    public function getAllPublishedPosts($locale)
    {
        return $this->getAllPublishedPostsAsQuery($locale)->getResult();
    }

    /**
     * Get last posts
     *
     * @param string $locale
     * @param int    $count Max count of returned posts
     *
     * @return array
     */
    public function getLastPosts($locale, $count = null)
    {
        $query = $this->getAllPublishedPostsAsQuery($locale);

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