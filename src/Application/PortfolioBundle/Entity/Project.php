<?php

namespace Application\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Imagine;

/**
 * Application\PortfolioBundle\Entity\Project
 *
 * @orm:Table(name="portfolio_projects")
 * @orm:Entity(repositoryClass="Application\PortfolioBundle\Repository\ProjectRepository")
 */
class Project
{

    /**
     * @var integer $id
     *
     * @orm:Column(name="id", type="integer")
     * @orm:Id
     * @orm:GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @orm:Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @orm:Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var text $description
     *
     * @orm:Column(name="description", type="text")
     */
    private $description;

    /**
     * @var text $url
     *
     * @orm:Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var \DateTime $date
     *
     * @orm:Column(type="datetime")
     */
    private $date;

    /**
     * @var \DateTime $created
     *
     * @orm:Column(type="datetime")
     * @gedmo:Timestampable(on="create")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @orm:Column(type="datetime")
     * @gedmo:Timestampable(on="update")
     */
    private $updated;

    /**
     * @var string $image
     *
     * @orm:Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @orm:ManyToMany(targetEntity="Application\PortfolioBundle\Entity\Category")
     * @orm:JoinTable(name="portfolio_projects_categories",
     *   joinColumns={
     *     @orm:JoinColumn(name="project_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @orm:JoinColumn(name="category_id", referencedColumnName="id")
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

    /**
     * Create thumbnail image to project
     * 
     * @param string $imagePath
     */
    public function setImage($imagePath)
    {
        try {
            // create thumbnail and save it to new file
            $filename = uniqid() . '.png';
            $imagine = new Imagine\Gd\Imagine();
            $imagePath = $imagine->open($imagePath);
            $imagePath->thumbnail(new Imagine\Image\Box(240, $imagePath->getSize()->getHeight()), Imagine\ImageInterface::THUMBNAIL_INSET)
                    ->crop(new Imagine\Image\Point(0, 0), new Imagine\Image\Box(240, 198))
                    ->save($this->getPathToUploads() . '/' . $filename);

            // remove old image file
            // @todo: refact
            $this->removeImage();

            $this->image = $filename;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
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