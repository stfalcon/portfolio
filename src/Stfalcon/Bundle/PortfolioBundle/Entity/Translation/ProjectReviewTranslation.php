<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Stfalcon\Bundle\PortfolioBundle\Entity\AbstractClass\AbstractTranslation;

/**
 * Class ProjectReviewTranslation
 *
 * @ORM\Entity()
 * @ORM\Table(name="project_review_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="project_review_lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ProjectReviewTranslation extends AbstractTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReview", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
