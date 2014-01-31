<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;

/**
 * Categories fixtures
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Create and load categories fixtures to database
     *
     * @param Doctrine\ORM\EntityManager $manager Entity manager object
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        // categories
        $development = new Category();
        $development->setName('Web Development');
        $development->setSlug('web-development');
        $development->setDescription('In work we use Symfony2.');

        $manager->persist($development);
        $manager->flush();

        $this->addReference('category-development', $development);
    }

    /**
     * Get order number
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }

}