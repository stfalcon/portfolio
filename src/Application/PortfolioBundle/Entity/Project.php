<?php

namespace Application\PortfolioBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Application\PortfolioBundle\Entity\Project
 *
 * @orm:Table(name="portfolio_projects")
 * @orm:Entity
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
     * @var text $description
     *
     * @orm:Column(name="description", type="text")
     */
    private $description;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
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
        $this->contributionRepos = new ArrayCollection();
    }

}