<?php

namespace Application\PortfolioBundle\Tests\Entity;

use Application\PortfolioBundle\Entity\Project;

class ProjectEntityTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyProjectIdisNull()
    {
        $project = new Project();
        $this->assertNull($project->getId());
    }

    public function testSetAndGetProjectName()
    {
        $name = "preorder.it";
        
        $project = new Project();
        $project->setName($name);

        $this->assertEquals($project->getName(), $name);
    }

    public function testSetAndGetProjectDescription()
    {
        $description = "Press-releases and reviews of the latest electronic novelties: mobile phones, cellphones, smartphones, laptops, tablets, netbooks, gadgets, e-books, photo and video cameras. The possibility to leave a pre-order.";

        $project = new Project();
        $project->setDescription($description);

        $this->assertEquals($project->getDescription(), $description);
    }

}