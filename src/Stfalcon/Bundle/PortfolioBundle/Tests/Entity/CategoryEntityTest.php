<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Entity;

use Stfalcon\Bundle\PortfolioBundle\Entity\Category;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;

/**
 * Test Category entity
 */
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

        $this->assertEquals($name, $category->getName());
    }

    public function testSetAndGetCategoryDescription()
    {
        $description = "Design can help you improve your sustainability credentials, create products and services that make people happy and it has positive benefits on business's bottom line.";

        $category = new Category();
        $category->setDescription($description);

        $this->assertEquals($description, $category->getDescription());
    }

    public function testSetAndGetCategorySlug()
    {
        $category = new Category();

        $slug = 'web-development';
        $category->setSlug($slug);

        $this->assertEquals($slug, $category->getSlug());
    }

    public function testGetAndAddCategoryProjects()
    {
        $category = new Category();

        $project = new Project();

        $category->addProject($project);
        $projects = $category->getProjects();

        $this->assertEquals($projects->count(), 1);
        $this->assertTrue(\is_a($projects, 'Doctrine\Common\Collections\ArrayCollection'));
    }

    public function testGetAndSetOrdernum()
    {
        $category = new Category();

        // check default value
        $this->assertEquals(0, $category->getOrdernum());

        // check set/get
        $category->setOrdernum(2);
        $this->assertEquals(2, $category->getOrdernum());
    }

}