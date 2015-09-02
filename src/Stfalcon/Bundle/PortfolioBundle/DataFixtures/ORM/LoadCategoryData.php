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
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        // categories
        $development = new Category();
        $development->setName('Web Development');
        $development->setSlug('web-development');
        $development->setDescription('In work we use Symfony2.');
        $development->setCost('20 000');

        $manager->persist($development);

        $mobileDevelopment = new Category();
        $mobileDevelopment->setName('Mobile Development');
        $mobileDevelopment->setSlug('mobile-development');
        $mobileDevelopment->setDescription('In work we use Android and IOS.');
        $mobileDevelopment->setCost('30 000');

        $manager->persist($mobileDevelopment);
        $manager->flush();

        $this->addReference('category-development', $development);
        $this->addReference('mobile-development', $mobileDevelopment);
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