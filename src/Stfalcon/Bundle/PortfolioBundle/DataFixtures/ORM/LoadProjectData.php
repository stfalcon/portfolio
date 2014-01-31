<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;

/**
 * Projects fixtures
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Create and load projects fixtures to database
     *
     * @param Doctrine\ORM\EntityManager $manager Entity manager object
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        // projects
        $preorder = new Project();
        $preorder->setName('preorder.it');
        $preorder->setSlug('preorder-it');
        $preorder->setUrl('http://preorder.it');
        $preorder->setDate(new \DateTime('now'));
        $preorder->setDescription('Press-releases and reviews of the latest electronic novelties. The possibility to leave a pre-order.');
        $preorder->setUsers('<dl><dt>art-director and designer</dt><dd>Oleg Ulasyuk</dd></dl>');
        $preorder->setOnFrontPage(0);
        $preorder->setOrdernum(0);
        $preorder->addCategory($manager->merge($this->getReference('category-development')));
        $manager->persist($preorder);

        $eprice = new Project();
        $eprice->setName('eprice.kz');
        $eprice->setSlug('eprice-kz');
        $eprice->setUrl('http://eprice.kz');
        $eprice->setDate(new \DateTime('now'));
        $eprice->setDescription('Comparison of the prices of mobile phones, computers, monitors, audio and video in Kazakhstan');
        $eprice->setOnFrontPage(1);
        $eprice->setOrdernum(1);
        $eprice->addCategory($manager->merge($this->getReference('category-development')));
        $manager->persist($eprice);

        $manager->flush();

        $this->addReference('project-preorder', $preorder);
        $this->addReference('project-eprice', $eprice);

        for ($i = 0; $i < 16; $i++) {
            $example = new Project();
            $example->setName('example.com_' . $i);
            $example->setSlug('example-com_' . $i);
            $example->setUrl('http://example.com');
            $example->setDate(new \DateTime('now'));
            $example->setDescription('As described in RFC 2606, we maintain a number of domains such as EXAMPLE.COM and EXAMPLE.ORG for documentation purposes. These domains may be used as illustrative examples in documents without prior coordination with us. They are not available for registration.');
            $example->setOnFrontPage(0);
            $example->setOrdernum(2 + $i);
            $example->addCategory($manager->merge($this->getReference('category-development')));
            $manager->persist($example);
        }
        $manager->flush();
    }

    /**
     * Get order number
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}