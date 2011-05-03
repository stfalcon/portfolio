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
        $development->setDescription('In work we use Symfony2.');
        
        $em->persist($development);
        $em->flush();

        $this->addReference('category-development', $development);
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}