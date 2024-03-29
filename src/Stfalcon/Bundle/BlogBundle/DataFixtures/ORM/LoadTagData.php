<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;

/**
 * Tags fixtures
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Create and load tags fixtures to database
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $kmTag = new Tag('Khmelnitsky');
        $manager->persist($kmTag);
        $kievTag = new Tag('Kyiv');
        $manager->persist($kievTag);
        $remoteTag = new Tag('remote');
        $manager->persist($remoteTag);

        $symfony2 = new Tag('symfony');
        $manager->persist($symfony2);

        $doctrine2 = new Tag('doctrine2');
        $manager->persist($doctrine2);

        $php = new Tag('php');
        $manager->persist($php);

        $design = (new Tag())
            ->setText('дизайн');
        $manager->persist($design);
        $this->addReference('tag-design', $design);

        $manager->flush();

        $this->addReference('tag-php', $php);
        $this->addReference('tag-doctrine2', $doctrine2);
        $this->addReference('tag-symfony2', $symfony2);

        $this->addReference('tag-km', $kmTag);
        $this->addReference('tag-kiev', $kievTag);
        $this->addReference('tag-remote', $remoteTag);
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
