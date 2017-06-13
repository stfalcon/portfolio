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
        $design = new Category();
        $design->setName('Web Design');
        $design->setSlug('web-design');
        $design->setDetails('web-design');
        $design->setDescription('In work we use Symfony2.');
        $design->setCost('10 000');
        $manager->persist($design);

        $development = new Category();
        $development->setName('Web Development');
        $development->setSlug('web-development');
        $development->setDetails('web-development');
        $development->setDescription('In work we use Symfony2.');
        $development->setCost('20 000');

        $manager->persist($development);

        $mobileDevelopment = new Category();
        $mobileDevelopment->setName('Mobile Development');
        $mobileDevelopment->setSlug('mobile-development');
        $mobileDevelopment->setDetails('mobile-development');
        $mobileDevelopment->setDescription('In work we use Android and IOS.');
        $mobileDevelopment->setCost('30 000');

        $manager->persist($mobileDevelopment);
        $manager->flush();

        $this->addReference('web-development', $development);
        $this->addReference('mobile-development', $mobileDevelopment);
        $this->addReference('web-design', $design);
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