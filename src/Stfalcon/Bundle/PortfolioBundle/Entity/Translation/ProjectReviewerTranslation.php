<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Stfalcon\Bundle\PortfolioBundle\Entity\AbstractClass\AbstractTranslation;

/**
 * Class ProjectReviewerTranslation
 *
 * @ORM\Entity()
 * @ORM\Table(name="project_reviewer_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="project_reviewer_lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class ProjectReviewerTranslation extends AbstractTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReviewer", inversedBy="translations")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $object;
}
