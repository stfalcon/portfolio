<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Translatable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stfalcon\Bundle\PortfolioBundle\Entity\Traits\TimestampableTrait;
use Stfalcon\Bundle\PortfolioBundle\Entity\Traits\TranslateTrait;
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
    use TimestampableTrait;
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
     * @ORM\OneToMany(
     *   targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\Translation\ProjectReviewTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"},
     *   orphanRemoval=true
     * )
     */
    private $translations;

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
     * @Gedmo\Translatable(fallback=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "10"
     * )
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="reviewer_project_status", type="string", length=255, nullable=true)
     */
    private $reviewerProjectStatus;

    /**
     * ProjectReview constructor.
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getReviewerProjectStatus()
    {
        return $this->reviewerProjectStatus;
    }

    /**
     * @param string $reviewerProjectStatus
     *
     * @return $this
     */
    public function setReviewerProjectStatus($reviewerProjectStatus)
    {
        $this->reviewerProjectStatus = $reviewerProjectStatus;

        return $this;
    }

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

    /**
     * @return string
     */
    public function __toString()
    {
        $result = $this->getProject() ? $this->getProject()->getName() : '';
        $result .= ' - ';
        $result .= $this->getReviewer() ? $this->getReviewer()->getName() : '';

        return $result;
    }
}
