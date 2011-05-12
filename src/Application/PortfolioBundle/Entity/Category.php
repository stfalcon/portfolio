<?php

namespace Application\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Application\PortfolioBundle\Entity\Category
 *
 * @orm:Table(name="portfolio_categories")
 * @orm:Entity(repositoryClass="Application\PortfolioBundle\Repository\CategoryRepository")
 */
class Category
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
     * @assert:NotBlank()
     * @assert:MinLenght(3)
     * @orm:Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @assert:NotBlank()
     * @assert:MinLenght(3)
     * @orm:Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var text $description
     *
     * @assert:NotBlank()
     * @assert:MinLenght(10)
     * @orm:Column(name="description", type="text")
     */
    private $description;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     *
     * @orm:ManyToMany(targetEntity="Application\PortfolioBundle\Entity\Project", mappedBy="categories")
     * @orm:OrderBy({"date" = "DESC"})
     */
    private $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
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

    public function getProjects()
    {
        return $this->projects;
    }

    public function addProject(\Application\PortfolioBundle\Entity\Project $project)
    {
        $this->projects[] = $project;
    }

    public function __toString()
    {
        return $this->getName();
    }

}