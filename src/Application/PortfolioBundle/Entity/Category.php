<?php

namespace Application\PortfolioBundle\Entity;

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

}