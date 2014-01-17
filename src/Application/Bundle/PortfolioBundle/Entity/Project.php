<?php

namespace Application\Bundle\PortfolioBundle\Entity;

use Application\Bundle\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Stfalcon\Bundle\PortfolioBundle\Entity\BaseProject;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="Application\Bundle\PortfolioBundle\Repository\ProjectRepository")
 * @ORM\Table(name="portfolio_projects")
 * @Vich\Uploadable
 */
class Project extends BaseProject
{
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\Bundle\PortfolioBundle\Entity\Category")
     * @ORM\JoinTable(name="portfolio_projects_categories",
     *   joinColumns={
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="projects_participants",
     *   joinColumns={
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $participants;

    /**
     * @var File $image
     *
     * @Assert\File(
     *     maxSize="4M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="project_image", fileNameProperty="image")
     */
    protected $imageFile;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\Bundle\MediaBundle\Entity\GalleryHasMedia",cascade={"persist"})
     * @ORM\JoinTable(name="projects_media",
     *   joinColumns={
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $media;

    /**
     * Initialization properties for new project entity
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    /**
     * Set imageFile
     *
     * @param File $imageFile
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Get imageFile
     *
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $participants
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function addMedia($media)
    {
        $this->media->add($media);
    }

    /**
     * @param Media $media
     */
    public function removeMedia( $media)
    {
        $this->media->removeElement($media);
    }
}
