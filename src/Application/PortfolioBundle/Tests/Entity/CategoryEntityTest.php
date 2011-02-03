<?php

namespace Application\PortfolioBundle\Tests\Entity;

use Application\PortfolioBundle\Entity\Category;

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

}