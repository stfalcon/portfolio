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
     * @param string  $slug
     * @param string  $locale
     * @param boolean $isAdmin
     *
     * @return Post|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findPostBySlugInLocale($slug, $locale, $isAdmin = false)
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.published = 1')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->setMaxResults(1);

        $this->addReviewFilter($qb, $isAdmin);

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Get all posts
     *
     * @param string  $locale
     * @param boolean $isAdmin
     *
     * @return Query
     */
    public function getAllPublishedPostsAsQuery($locale, $isAdmin = false)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.published = 1')
            ->orderBy('p.created', 'DESC');

        $this->addReviewFilter($qb, $isAdmin);

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery();
    }

    /**
     * Get all posts
     *
     * @param string  $locale
     * @param boolean $isAdmin
     *
     * @return array
     */
    public function getAllPublishedPosts($locale, $isAdmin = false)
    {
        return $this->getAllPublishedPostsAsQuery($locale, $isAdmin)->getResult();
    }

    /**
     * Get last posts
     *
     * @param string $locale
     * @param int $count Max count of returned posts
     * @param boolean $isAdmin
     *
     * @return array
     */
    public function getLastPosts($locale, $count = null, $isAdmin = false)
    {
        $query = $this->getAllPublishedPostsAsQuery($locale, $isAdmin);

        if ((int)$count) {
            $query->setMaxResults($count);
        }

        return $query->getResult();
    }

    /**
     * @param Tag     $tag
     * @param string  $locale
     * @param boolean $isAdmin
     *
     * @return Query
     */
    public function findPostsByTagAsQuery(Tag $tag, $locale, $isAdmin = false)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('t.id', ':tag_id'))
            ->andWhere($qb->expr()->eq('p.published', true))
            ->leftJoin('p.tags', 't')
            ->setParameter('tag_id', $tag->getId())
            ->orderBy('p.created', 'DESC');

        $this->addReviewFilter($qb, $isAdmin);

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery();
    }

    /**
     * Find related posts by tags
     *
     * @param string $locale Locale
     * @param Post   $post   Current post
     * @param int    $limit  Related posts count
     * @param boolean $isAdmin
     *
     * @return array
     */
    public function findRelatedPostsToCurrentPost($locale, $post, $limit = 6, $isAdmin = false)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->in('t.text', $post->getTags()->getValues()))
           ->andWhere($qb->expr()->eq('p.published', true))
           ->andWhere($qb->expr()->neq('p', ':post'))
           ->setParameter('post', $post)
           ->addOrderBy('p.created', 'desc')
           ->join('p.tags', 't');

        $this->addReviewFilter($qb, $isAdmin);

        $this->addLocaleFilter($locale, $qb);

        return $qb->setMaxResults($limit)
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find all in array
     *
     * @param array $postsId Posts id
     * @param boolean $isAdmin
     *
     * @return array Posts
     */
    public function findAllInArray($postsId, $isAdmin = false)
    {
        $qb = $this->createQueryBuilder('p');

        $this->addReviewFilter($qb, $isAdmin);

        return $qb->andwhere($qb->expr()->in('p.id', ':posts'))
            ->setParameter('posts', $postsId)
            ->addOrderBy('p.created', 'desc')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get posts query by user
     *
     * @param User    $user User
     * @param string  $locale Locale
     * @param boolean $isAdmin
     *
     * @return array
     */
    public function getPostsQueryByUser(User $user, $locale, $isAdmin = false)
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where($qb->expr()->eq('p.author', ':user_id'))
            ->andWhere($qb->expr()->eq('p.published', true))
            ->orderBy('p.created', 'DESC')
            ->setParameter('user_id', $user->getId());

        $this->addReviewFilter($qb, $isAdmin);

        $this->addLocaleFilter($locale, $qb);

        return $qb->getQuery();
    }

    /**
     * @param string       $locale
     * @param QueryBuilder $qb
     */
    private function addLocaleFilter($locale, QueryBuilder $qb)
    {
        if ('ru' !== $locale) {
            $qb->innerJoin('p.translations', 'tr')
                ->andWhere('tr.locale = :locale')
                ->setParameter('locale', $locale)
                ->andWhere($qb->expr()->isNotNull('tr.content'))
                ->addGroupBy('p.id');
        } else {
            $qb->andWhere($qb->expr()->neq('p.title', ':empty'))
               ->andWhere($qb->expr()->isNotNull('p.title'))
                ->setParameter('empty', '');
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param boolean      $isAdmin
     */
    private function addReviewFilter(QueryBuilder $qb, $isAdmin)
    {
        if (!$isAdmin) {
            $qb->andWhere($qb->expr()->eq('p.previewMode', 0))
                ->orWhere($qb->expr()->isNull('p.previewMode'));
        }
    }
}
