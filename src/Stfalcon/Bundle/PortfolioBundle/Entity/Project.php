<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use Imagine;

/**
 * Project entity
 *
 * @ORM\Table(name="portfolio_projects")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\PortfolioBundle\Repository\ProjectRepository")
 * @Vich\Uploadable
 */
class Project
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = '';

    /**
     * @var string $slug
     *
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string $description
     *
     * @Assert\NotBlank()
     * @Assert\MinLength(10)
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string $url
     *
     * @Assert\Url
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

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
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var int $ordernum
     *
     * @ORM\Column(name="ordernum", type="integer")
     */
    private $ordernum = 0;

    /**
     * Check if this project can be published on main page of the site
     *
     * @var bool $onFrontPage
     *
     * @ORM\Column(name="onFrontPage", type="boolean")
     */
    private $onFrontPage = true;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\Category")
     * @ORM\JoinTable(name="portfolio_projects_categories",
     *   joinColumns={
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *   }
     * )
     */
    private $categories;

    /**
     * @var string $users
     *
     * @ORM\Column(name="users", type="text", nullable=true)
     */
    private $users;

    /**
     * Initialization properties for new project entity
     *
     * @return void
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Get post id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get project categories
     *
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add category to project
     *
     * @param Category $category Category entity
     *
     * @return void
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    }

    /**
     * Set categories collection to project
     *
     * @param \Doctrine\Common\Collections\Collection $categories Categories collection
     *
     * @return void
     */
    public function setCategories(\Doctrine\Common\Collections\Collection $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Set project name
     *
     * @param type $name A text of project name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get project name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set project slug
     *
     * @param string $slug Unique text identifier
     *
     * @return void
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get project slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set project description
     *
     * @param string $description A text of description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get project description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set project url
     *
     * @param string $url A url for project
     *
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get project url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set date when project has been realized
     *
     * @param \DateTime $date Date when project has been realized
     *
     * @return void
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Get date when project has been realized
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get image filename
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image and create thumbnail
     *
     * @param string $image Full path to image file
     *
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Remove thumbnail image file
     *
     * @return boolean
     */
    public function removeImage()
    {
        if ($this->getImagePath() && \file_exists($this->getImagePath())) {
            unlink($this->getImagePath());

            return true;
        }

        return false;
    }

    /**
     * Get list of users who worked on the project (as html)
     *
     * @return string
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set list of users who worked on the project (as html)
     *
     * @param string $users A list in html format
     *
     * @return void
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * Set time when project created
     *
     * @param \DateTime $created A time when project created
     *
     * @return void
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * Get time when project created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set time when project updated
     *
     * @param \DateTime $updated A time when project updated
     *
     * @return void
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get time when project updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set project ordernum
     *
     * @param int $ordernum
     *
     * @return void
     */
    public function setOrdernum($ordernum)
    {
        $this->ordernum = $ordernum;
    }

    /**
     * Get project ordernum
     *
     * @return int
     */
    public function getOrdernum()
    {
        return $this->ordernum;
    }

    /**
     * Set onFrontPage
     *
     * @param bool $onFrontPage
     *
     * @return void
     */
    public function setOnFrontPage($onFrontPage)
    {
        $this->onFrontPage = $onFrontPage;
    }

    /**
     * Get onFrontPage
     *
     * @return bool
     */
    public function getOnFrontPage()
    {
        return $this->onFrontPage;
    }

    /**
     * Set imageFile
     *
     * @param File $imageFile
     *
     * @return void
     */
    public function setImageFile($imageFile)
    {
        if (null === $imageFile) {
            return;
        }

        $this->imageFile = $imageFile;
        $imagine = new Imagine\Gd\Imagine();
        $imagePath = $imagine->open($this->imageFile->getPathName());
        $imagePath->thumbnail(new Imagine\Image\Box(240, $imagePath->getSize()->getHeight()), Imagine\Image\ImageInterface::THUMBNAIL_INSET)
                ->crop(new Imagine\Image\Point(0, 0), new Imagine\Image\Box(240, 198))
                ->save($this->imageFile->getPathName(), array('format' => 'png'));

        $this->setUpdated(new \DateTime());
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
     * This method allows a class to decide how it will react when it is treated like a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}