<?php

namespace Application\PortfolioBundle\Tests\Entity;

use Application\PortfolioBundle\Entity\Category;
use Application\PortfolioBundle\Entity\Project;

class CategoryEntityTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyCategoryIdisNull()
    {
        $category = new Category();
        $this->assertNull($category->getId());
    }

    public function testSetAndGetCategoryName()
    {
        $name = "Design";

        $category = new Category();
        $category->setName($name);

        $this->assertEquals($category->getName(), $name);
    }

    public function testSetAndGetCategoryDescription()
    {
        $description = "Design can help you improve your sustainability credentials, create products and services that make people happy and it has positive benefits on business's bottom line.";

        $category = new Category();
        $category->setDescription($description);

        $this->assertEquals($category->getDescription(), $description);
    }

    public function testSetAndGetCategorySlug()
    {
        $category = new Category();

        $slug = 'web-development';
        $category->setSlug($slug);

        $this->assertEquals($category->getSlug(), $slug);
    }

    public function testGetAndAddCategoryProjects()
    {
        $category = new Category();

        $project = new Project();

        $category->addProject($project);
        $projects = $category->getProjects();

        $this->assertEquals($projects->count(), 1);
        $this->assertTrue(\is_a($projects, 'Doctrine\Common\Collections\ArrayCollection'), 2);
    }

}