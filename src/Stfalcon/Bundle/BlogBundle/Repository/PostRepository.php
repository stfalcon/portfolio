<?php

namespace Stfalcon\Bundle\BlogBundle\Repository;

use Application\Bundle\UserBundle\Entity\User;
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
     * @param int $count Max count of returned posts
     *
     * @return array
     */
    public function getLastPosts($locale, $count = null)
    {
        $query = $this->getAllPublishedPostsAsQuery($locale);

        if ((int)$count) {
            $query->setMaxResults($count);
        }

        return $query->getResult();
    }

    /**
     * @param Tag $tag
     * @param string $locale
     *
     * @return Query
     */
    public function findPostsByTagAsQuery(Tag $tag, $locale)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('t.id', ':tag_id'))
            ->andWhere($qb->expr()->eq('p.published', true))
            ->leftJoin('p.tags', 't')
            ->setParameter('tag_id', $tag->getId())
            ->orderBy('p.created', 'DESC');

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery();
    }

    /**
     * Find related posts by tags
     *
     * @param string $locale Locale
     * @param Post   $post   Current post
     * @param int    $limit  Related posts count
     *
     * @return array
     */
    public function findRelatedPostsToCurrentPost($locale, $post, $limit = 6)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->in('t.text', $post->getTags()->getValues()))
           ->andWhere($qb->expr()->eq('p.published', true))
           ->andWhere($qb->expr()->neq('p', ':post'))
           ->setParameter('post', $post)
           ->addOrderBy('p.created', 'desc')
           ->join('p.tags', 't');

        $this->addLocaleFilter($locale, $qb);

        return $qb->setMaxResults($limit)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find all in array
     *
     * @param array $postsId Posts id
     *
     * @return array Posts
     */
    public function findAllInArray($postsId)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb->where($qb->expr()->in('p.id', ':posts'))
            ->setParameter('posts', $postsId)
            ->addOrderBy('p.created', 'desc')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get posts query by user
     *
     * @param User $user User
     * @param string $locale Locale
     *
     * @return array
     */
    public function getPostsQueryByUser(User $user, $locale)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('p.author', ':user_id'))
            ->andWhere($qb->expr()->eq('p.published', true))
            ->orderBy('p.created', 'DESC')
            ->setParameter('user_id', $user->getId());

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery();
    }

    /**
     * @param string $locale
     * @param QueryBuilder $qb
     */
    private function addLocaleFilter($locale, QueryBuilder $qb)
    {
        if ($locale != 'ru') {
            $qb->innerJoin('p.translations', 'tr')
                ->andWhere('tr.locale = :locale')
                ->setParameter('locale', $locale)
                ->andWhere($qb->expr()->isNotNull('tr.content'))
                ->addGroupBy('p.id');
        }
    }

}
