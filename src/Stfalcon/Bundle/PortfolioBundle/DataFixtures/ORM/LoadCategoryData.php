<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\Category;

/**
 * Categories fixtures.
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Create and load categories fixtures to database.
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        // categories
        $development = (new Category())
            ->setName('Web Development')
            ->setTitle('Web Development')
            ->setShortName('Web Development')
            ->setSlug('web-development')
            ->setDetails('web-development')
            ->setDescription('In work we use Symfony2.')
            ->setShowInServices(true)
            ->setShowInProjects(true)
            ->setCost('20 000');

        $manager->persist($development);

        $mobileDevelopment = (new Category())
            ->setName('Mobile Development')
            ->setTitle('Mobile Development')
            ->setShortName('Mobile Development')
            ->setSlug('mobile-development')
            ->setDetails('mobile-development')
            ->setDescription('In work we use Android and IOS.')
            ->setShowInServices(true)
            ->setShowInProjects(true)
            ->setCost('30 000');

        $manager->persist($mobileDevelopment);

        $webDesign = (new Category())
            ->setName('Consulting audit')
            ->setTitle('Consulting audit')
            ->setShortName('Consulting audit')
            ->setSlug('consulting-audit')
            ->setDetails('Consulting audit')
            ->setDescription('In work we use Paint.')
            ->setShowInServices(true)
            ->setShowInProjects(false)
            ->setCost('30 000');

        $manager->persist($webDesign);

        $manager->flush();

        $this->addReference('category-development', $development);
        $this->addReference('category-web', $webDesign);
        $this->addReference('category-mobile', $mobileDevelopment);
    }

    /**
     * Get order number.
     *
     * @return int
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
