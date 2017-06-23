<?php

namespace Stfalcon\Bundle\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Category entity. It groups projects in portfolio
 *
 * @ORM\Table(name="portfolio_categories")
 * @ORM\Entity(repositoryClass="Stfalcon\Bundle\PortfolioBundle\Repository\CategoryRepository")
 *
 * @Gedmo\TranslationEntity(class="Stfalcon\Bundle\PortfolioBundle\Entity\CategoryTranslation")
 */
class Category implements Translatable
{
    use TimestampableEntity;

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
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Translatable(fallback=true)
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = '';

    /**
     * @var string $shortName
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Translatable(fallback=true)
     * @ORM\Column(name="short_name", type="string", length=255)
     */
    private $shortName = '';

    /**
     * @var string $slug
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var string $description
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Translatable(fallback=true)
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string $details
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3"
     * )
     * @Gedmo\Translatable(fallback=true)
     * @ORM\Column(name="details", type="text")
     */
    private $details;

    /**
     * @var string $title Title
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    private $title;

    /**
     * @var string $metaDescription Meta description
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var string $metaKeywords Meta keywords
     *
     * @Gedmo\Translatable(fallback=true)
     *
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     */
    private $metaKeywords;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *      targetEntity="Stfalcon\Bundle\PortfolioBundle\Entity\Project",
     *      mappedBy="categories", fetch="EXTRA_LAZY"
     * )
     * @ORM\OrderBy({"ordernum" = "ASC", "date" = "DESC"})
     */
    private $projects;

    /**
     *
     * @var integer
     *
     * @ORM\Column(name="ordernum", type="integer")
     */
    private $ordernum = 0;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $cost;

    /**
     * @ORM\OneToMany(
     *   targetEntity="CategoryTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * @Gedmo\Locale
     */
    private $locale;

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name="show_in_services", type="boolean")
     */
    private $showInServices = false;

    /**
     *
     * @var boolean
     *
     * @ORM\Column(name="show_in_projects", type="boolean")
     */
    private $showInProjects = true;
    /**
     * Initialization properties for new category entity
     */
    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->cost = '';
        $this->translations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $result = 'New Category';

        if (null !== $this->getId()) {
            $result = $this->getName();
        }

        return $result;
    }

    /**
     * Get category id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category name
     *
     * @param string $name Text for category name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get category name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set category slug
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
     * Get category slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set category description
     *
     * @param string $description Text for category description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get category description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get category projects
     *
     * @return ArrayCollection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add project to category
     *
     * @param Project $project Project object
     *
     * @return void
     */
    public function addProject(Project $project)
    {
        $this->projects[] = $project;
    }

    /**
     * Get order num
     *
     * @return integer
     */
    public function getOrdernum()
    {
        return $this->ordernum;
    }

    /**
     * Set order num
     *
     * @param integer $ordernum
     */
    public function setOrdernum($ordernum)
    {
        $this->ordernum = $ordernum;
    }

    /**
     * @param string $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param CategoryTranslation $categoryTranslation
     */
    public function addTranslations(CategoryTranslation $categoryTranslation)
    {
        if (!$this->translations->contains($categoryTranslation)) {
            $this->translations->add($categoryTranslation);
            $categoryTranslation->setObject($this);
        }
    }

    /**
     * @param CategoryTranslation $categoryTranslation
     */
    public function addTranslation(CategoryTranslation $categoryTranslation)
    {
        if (!$this->translations->contains($categoryTranslation)) {
            $this->translations->add($categoryTranslation);
            $categoryTranslation->setObject($this);
        }
    }

    /**
     * @param CategoryTranslation $categoryTranslation
     */
    public function removeTranslation(CategoryTranslation $categoryTranslation)
    {
        $this->translations->removeElement($categoryTranslation);
    }

    /**
     * @param ArrayCollection $translations
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    /**
     * @param string $locale
     */
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Get metaDescription
     *
     * @return string MetaDescription
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set meta description
     *
     * @param string $metaDescription Meta description
     *
     * @return $this
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get meta keywords
     *
     * @return string Meta keywords
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set meta keywords
     *
     * @param string $metaKeywords Meta keywords
     *
     * @return $this
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get title
     *
     * @return string Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return boolean
     */
    public function isShowInServices()
    {
        return $this->showInServices;
    }

    /**
     * @param boolean $showInServices
     */
    public function setShowInServices($showInServices)
    {
        $this->showInServices = $showInServices;
    }

    /**
     * @return boolean
     */
    public function isShowInProjects()
    {
        return $this->showInProjects;
    }

    /**
     * @param boolean $showInProjects
     */
    public function setShowInProjects($showInProjects)
    {
        $this->showInProjects = $showInProjects;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }
}
