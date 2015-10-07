<?php

namespace Stfalcon\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Stfalcon\Bundle\BlogBundle\Entity\Comment;
use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * CommentRepository
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class CommentRepository extends EntityRepository
{
    /**
     * Find comments by post with pagination
     *
     * @param Post   $post     Post
     * @param string $language Language
     *
     * @return null|Comment[]
     */
    public function findCommentsByPost(Post $post, $language)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->where($qb->expr()->eq('c.post', ':post'))
                  ->andWhere($qb->expr()->eq('c.language', ':lang'))
                  ->andWhere($qb->expr()->isNull('c.parent'))
                  ->setParameters([
                      'post' => $post,
                      'lang' => $language,
                  ])
                  ->orderBy('c.parent', 'ASC')
                  ->addOrderBy('c.createdAt', 'DESC')
                  ->getQuery()
                  ->getResult();
    }
}
