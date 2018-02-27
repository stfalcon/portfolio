<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Translatable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Stfalcon\Bundle\PortfolioBundle\Entity\Traits\TimestampableTrait;
use Stfalcon\Bundle\PortfolioBundle\Entity\Traits\TranslateTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ProjectReviewer.
 *
 * @ORM\Table(name="portfolio_projects_reviewer")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\PortfolioBundle\Repository\ProjectReviewerRepository")
 *
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\PortfolioBundle\Entity\Translation\ProjectReviewerTranslation")
 *
 * @Vich\Uploadable
 */
class ProjectReviewer implements Translatable
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
     *   targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\Translation\ProjectReviewerTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"},
     *   orphanRemoval=true
     * )
     */
    private $translations;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReview", mappedBy="reviewer"
     * , cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $projectReviews;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     *
     * @Gedmo\Translatable(fallback=true)
     */
    private $name;

    /**
     * @var File
     *
     * @Assert\File(
     *     maxSize="4M",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"}
     * )
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="photo")
     */
    protected $photoFile;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * ProjectReviewer constructor.
     */
    public function __construct()
    {
        $this->projectReviews = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * @return ArrayCollection
     */
    public function getProjectReviews()
    {
        return $this->projectReviews;
    }

    /**
     * @param ArrayCollection $projectReviews
     *
     * @return $this
     */
    public function setProjectReviews($projectReviews)
    {
        $this->projectReviews = $projectReviews;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return File
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * @param File $photoFile
     *
     * @return $this
     */
    public function setPhotoFile($photoFile)
    {
        $this->setUpdatedAt(new \DateTime());
        $this->photoFile = $photoFile;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     *
     * @return $this
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
