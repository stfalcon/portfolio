<?php

namespace Application\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Application\PortfolioBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($em)
    {
        // categories
        $development = new Category();
        $development->setName('Web Development');
        $development->setSlug('web-development');
        $development->setDescription('In work we use PHP (Zend Framework, Doctrine, Smarty, PEAR), JavaScript (jQuery, YUI, MooTools), SQL (MySQL, PgSQL), HTML/XHTML, CSS, bugtrackers and source control systems.');
        
        $development->addProject($em->merge($this->getReference('project-preorder')));
        $development->addProject($em->merge($this->getReference('project-eprice')));

        $em->persist($development);
        $em->flush();

        $this->addReference('category-development', $development);
    }

    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}