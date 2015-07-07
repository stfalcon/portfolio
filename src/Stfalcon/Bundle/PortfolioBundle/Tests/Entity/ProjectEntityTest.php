<?php

namespace Stfalcon\Bundle\PortfolioBundle\Tests\Entity;

use Application\Bundle\UserBundle\Entity\User;
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

    public function testSetAndGetAndAddProjectParticipants()
    {
        $project = new Project();

        $user = new User();

        $project->addParticipant($user);
        $participants = $project->getParticipants();

        $this->assertEquals($participants->count(), 1);
        $this->assertTrue(\is_a($participants, 'Doctrine\Common\Collections\ArrayCollection'), 2);

        $project->setParticipants($participants);

        $this->assertEquals($project->getParticipants(), $participants);
    }

    public function testSetAndGetProjectTags()
    {
        $project = new Project();

        $tags = 'php, symfony2';
        $project->setTags($tags);

        $this->assertEquals($project->getTags(), $tags);
    }

    public function testSetAndGetProjectPublished()
    {
        $project = new Project();

        $this->assertTrue($project->isPublished());

        $project->setPublished(false);

        $this->assertFalse($project->isPublished());
    }

    public function testSetAndGetProjectShowCase()
    {
        $project = new Project();

        $this->assertFalse($project->isShowCase());

        $project->setShowCase(true);

        $this->assertTrue($project->isShowCase());
    }

    public function testSetAndGetProjectShadow()
    {
        $project = new Project();

        $this->assertTrue($project->getShadow());

        $project->setShadow(false);

        $this->assertFalse($project->getShadow());
    }

    public function testSetAndGetProjectMetaKeywords()
    {
        $project = new Project();

        $keywords = 'php, symfony2';
        $project->setMetaKeywords($keywords);

        $this->assertEquals($project->getMetaKeywords(), $keywords);
    }

    public function testSetAndGetProjectMetaDescription()
    {
        $project = new Project();

        $description = 'php symfony2 performance';
        $project->setMetaDescription($description);

        $this->assertEquals($project->getMetaDescription(), $description);
    }

    public function testSetAndGetAndAddProjectRelativeProjects()
    {
        $project = new Project();
        $relativeProject = new Project();

        $project->addRelativeProject($relativeProject);
        $relativeProjects = $project->getRelativeProjects();

        $this->assertEquals($relativeProjects->count(), 1);
        $this->assertTrue(\is_a($relativeProjects, 'Doctrine\Common\Collections\ArrayCollection'), 2);

        $project->setRelativeProjects($relativeProjects);

        $this->assertEquals($project->getRelativeProjects(), $relativeProjects);
    }
}