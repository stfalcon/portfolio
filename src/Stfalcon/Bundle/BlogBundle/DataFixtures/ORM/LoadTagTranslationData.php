<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\BlogBundle\Entity\Tag;
use Stfalcon\Bundle\BlogBundle\Entity\TagTranslation;

/**
 * LoadTagTranslationData
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class LoadTagTranslationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Create and load tags fixtures to database
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        /** @var Tag $phpTag */
        /** @var Tag $doctrine2Tag */
        /** @var Tag $symfony2Tag */
        /** @var Tag $designTag */
        $phpTag       = $this->getReference('tag-php');
        $doctrine2Tag = $this->getReference('tag-doctrine2');
        $symfony2Tag  = $this->getReference('tag-symfony2');
        $designTag    = $this->getReference('tag-design');

        $symfony2 = (new TagTranslation())
            ->setLocale('en')
            ->setField('text')
            ->setContent('symfony2')
            ->setObject($symfony2Tag);
        $manager->persist($symfony2);

        $doctrine2 = (new TagTranslation())
            ->setLocale('en')
            ->setField('text')
            ->setContent('doctrine2')
            ->setObject($doctrine2Tag);
        $manager->persist($doctrine2);

        $php = (new TagTranslation())
            ->setLocale('en')
            ->setField('text')
            ->setContent('php')
            ->setObject($phpTag);
        $manager->persist($php);

        $design = (new TagTranslation())
            ->setLocale('en')
            ->setField('text')
            ->setContent('design')
            ->setObject($designTag);
        $manager->persist($design);

        $manager->flush();
    }

    /**
     * Get order number
     *
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
