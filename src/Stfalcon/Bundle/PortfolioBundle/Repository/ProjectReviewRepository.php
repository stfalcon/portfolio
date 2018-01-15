<?php

namespace Stfalcon\Bundle\PortfolioBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReview;

class ProjectReviewRepository extends EntityRepository
{
    /**
     * Get active reviews for projects
     *
     * @param ArrayCollection $projects
     *
     * @return mixed
     */
    public function getActiveReviews($projects)
    {
        $qb = $this->createQueryBuilder('pr');
        $qb->andWhere($qb->expr()->eq('pr.active', true))
            ->andWhere($qb->expr()->in('pr.project', ':projects'))
            ->setParameter(':projects', $projects);
        $reviews = $qb->getQuery()->execute();

        $result = [];
        /** @var Project $project */
        foreach ($projects as $project) {
            $result[$project->getId()] = ['project' => $project, 'review' => null];
        }
        /** @var ProjectReview $review */
        foreach ($reviews as $review) {
            $result[$review->getProject()->getId()]['review'] = $review;
        }

        return $result;
    }
}
