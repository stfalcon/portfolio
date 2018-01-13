<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Gedmo\Translatable\Translatable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stfalcon\Bundle\PortfolioBundle\Entity\Traits\TimestampAbleTrait;
use Stfalcon\Bundle\PortfolioBundle\Traits\TranslateTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProjectReview.
 *
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\PortfolioBundle\Repository\ProjectReviewRepository")
 * @ORM\Table(name="portfolio_projects_review")
 *
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\PortfolioBundle\Entity\Translation\ProjectReviewTranslation")
 */
class ProjectReview implements Translatable
{
    use TimestampAbleTrait;
    use TranslateTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var ProjectReviewer
     *
     * @ORM\ManyToOne(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReviewer", inversedBy="projectReviews")
     */
    private $reviewer;
    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\Project", inversedBy="projectReviews")
     */
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "10"
     * )
     */
    private $text;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ProjectReviewer
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }

    /**
     * @param ProjectReviewer $reviewer
     *
     * @return $this
     */
    public function setReviewer($reviewer)
    {
        $this->reviewer = $reviewer;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     *
     * @return $this
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}
