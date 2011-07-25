<?php

namespace Application\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Imagine;

/**
 * Application\PortfolioBundle\Entity\Project
 *
 * @ORM\Table(name="portfolio_projects")
 * @ORM\Entity(repositoryClass="Application\PortfolioBundle\Repository\ProjectRepository")
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
    private $name;

    /**
     * @var string $slug
     *
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var text $description
     *
     * @Assert\NotBlank()
     * @Assert\MinLength(10)
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var text $url
     *
     * @Assert\Url()
     * @ORM\Column(name="url", type="string", length=255)
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
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Application\PortfolioBundle\Entity\Category")
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

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setSlug($slug)
    {
        // @todo: add filter
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getImage()
    {
        return ($this->image) ? $this->getPathToUploads() . '/' . $this->image : null;
    }

    public function getImageFilename()
    {
        return $this->image;
    }

    /**
     * Create thumbnail image to project
     * 
     * @param string $imagePath
     */
    public function setImage($imagePath)
    {
        // create thumbnail and save it to new file
        $filename = uniqid() . '.png';
        $imagine = new Imagine\Gd\Imagine();
        $imagePath = $imagine->open($imagePath);
        $imagePath->thumbnail(new Imagine\Image\Box(240, $imagePath->getSize()->getHeight()), Imagine\Image\ImageInterface::THUMBNAIL_INSET)
                ->crop(new Imagine\Image\Point(0, 0), new Imagine\Image\Box(240, 198))
                ->save($this->getPathToUploads() . '/' . $filename);

        // remove old image file
        // @todo: refact
        $this->removeImage();

        $this->image = $filename;
    }

    /**
     * Remove thumbnail image file
     *
     * @return boolean
     */
    public function removeImage()
    {
        if ($this->getImage() && \file_exists($this->getImage())) {
            unlink($this->getImage());
            return true;
        }
        
        return false;
    }

    /**
     * Get path to the uploaded files of the project
     * 
     * @return string
     */
    public function getPathToUploads()
    {
        return realpath(__DIR__ . '/../Resources/public/uploads/projects');
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function addCategory(\Application\PortfolioBundle\Entity\Category $category)
    {
        $this->categories[] = $category;
    }

    public function setCategories(\Doctrine\Common\Collections\Collection $categories)
    {
        $this->categories = $categories;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

}
