<?php

namespace Stfalcon\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;

/**
 * PostRepository
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class PostRepository extends EntityRepository
{
    /**
     * @param string $slug
     * @param string $locale
     *
     * @return Post|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findPostBySlugInLocale($slug, $locale)
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.published = 1')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->setMaxResults(1);

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery()->getOneOrNullResult();
    }

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
        $this->addLocaleFilter($locale, $qb);

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
     * @param Tag    $tag
     * @param string $locale
     *
     * @return Query
     */
    public function findPostsByTagAsQuery($tag, $locale)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->leftJoin('p.tags', 't')
            ->where('t.id = :tagId')
            ->andWhere('p.published = 1')
            ->setParameter('tagId', $tag->getId());

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery();
    }

    /**
     * @param string       $locale
     * @param QueryBuilder $qb
     */
    private function addLocaleFilter($locale, QueryBuilder $qb)
    {
        if ($locale != 'ru') {
            $qb->innerJoin('p.translations', 'tr')
                ->andWhere('tr.locale = :locale')
                ->setParameter('locale', $locale)
                ->andWhere($qb->expr()->isNotNull('tr.content'));
        }
    }

}