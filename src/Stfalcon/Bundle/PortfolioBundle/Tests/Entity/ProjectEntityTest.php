<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Entity;

use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;

/**
 * Test Project entity
 */
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

    public function testSetAndGetProjectDate()
    {
        $project = new Project();

        $date = new \DateTime('now');
        $project->setDate($date);

        $this->assertEquals($project->getDate(), $date);
    }

    public function testSetAndGetProjectImage()
    {
        $project = new Project();
        $project->setImage('image.jpg');
        $this->assertEquals($project->getImage(), 'image.jpg');
    }

    public function testGetProjectCreated()
    {
        $project = new Project();

        $this->assertEquals($project->getCreated(), null);
    }

    public function testGetProjectUpdated()
    {
        $project = new Project();

        $this->assertEquals($project->getUpdated(), null);
    }

    public function testSetAndGetProjectSlug()
    {
        $project = new Project();

        $slug = 'preorder-it';
        $project->setSlug($slug);

        $this->assertEquals($project->getSlug(), $slug);
    }

    public function testSetAndGetProjectUrl()
    {
        $project = new Project();

        $url = 'http://preorder.it';
        $project->setUrl($url);

        $this->assertEquals($project->getUrl(), $url);
    }

    public function testSetAndGetAndAddProjectCategories()
    {
        $project = new Project();

        $category = new Category();

        $project->addCategory($category);
        $categories = $project->getCategories();

        $this->assertEquals($categories->count(), 1);
        $this->assertTrue(\is_a($categories, 'Doctrine\Common\Collections\ArrayCollection'), 2);

        $project->setCategories($categories);

        $this->assertEquals($project->getCategories(), $categories);
    }

    public function testSetAndGetProjectUsers()
    {
        $users = '<dl><dt>art-director and designer</dt><dd>Oleg Ulasyuk</dd></dl>';

        $project = new Project();
        $project->setUsers($users);

        $this->assertEquals($project->getUsers(), $users);
    }

}