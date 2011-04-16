<?php

namespace Application\PortfolioBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Application\PortfolioBundle\Entity\Category
 *
 * @orm:Table(name="portfolio_categories")
 * @orm:Entity
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
     * @orm:Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text $description
     *
     * @orm:Column(name="description", type="text")
     */
    private $description;

//    /**
//     * @var Doctrine\Common\Collections\ArrayCollection
//     *
//     * @orm:ManyToMany(targetEntity="Application\PortfolioBundle\Entity\Project")
//     * @orm:JoinTable(name="portfolio_projects_categories",
//     *   joinColumns={
//     *     @orm:JoinColumn(name="category_id", referencedColumnName="id")
//     *   },
//     *   inverseJoinColumns={
//     *     @orm:JoinColumn(name="project_id", referencedColumnName="id")
//     *   }
//     * )
//     * @orm:OrderBy({"date" = "DESC"})
//     */
    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     *
     * @orm:ManyToMany(targetEntity="Application\PortfolioBundle\Entity\Project", mappedBy="categories")
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