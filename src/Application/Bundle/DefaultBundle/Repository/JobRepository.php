<?php

namespace Application\Bundle\DefaultBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Application\Bundle\DefaultBundle\Entity\Job;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;

class JobRepository extends EntityRepository
{
    /**
     * @param string $slug
     *
     * @return Job|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findJobBySlug($slug)
    {
        $qb = $this->createQueryBuilder('j')
            ->andWhere('j.active = 1')
            ->andWhere('j.slug = :slug')
            ->setParameter('slug', $slug)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Tag $tag
     *
     * @return Query
     */
    public function findJobsByTagAsQuery(Tag $tag)
    {
        $qb = $this->createQueryBuilder('j');

        $qb->where($qb->expr()->eq('t.id', ':tag_id'))
            ->andWhere($qb->expr()->eq('j.active', true))
            ->leftJoin('j.tags', 't')
            ->setParameter('tag_id', $tag->getId())
            ->orderBy('j.created', 'DESC');

        return $qb->getQuery();
    }
}