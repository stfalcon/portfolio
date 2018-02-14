<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * Posts fixtures
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Create and load posts fixtures to database
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $adminUser = $this->getReference('user-admin');
        $secondUser = $this->getReference('user-second');

        $symfonyTag = $this->getReference('tag-symfony2');
        $doctrine2Tag = $this->getReference('tag-doctrine2');
        // posts
        $postFirst = new Post();
        $postFirst->setTitle('My first post')
        ->setSlug('my-first-post')
        ->setText('In work we use Symfony2.<!--more-->And text after cut')
        ->addTag($symfonyTag)
        ->addTag($doctrine2Tag)
        ->setAuthor($adminUser)
        ->setPublished(true);
        $manager->persist($postFirst);
        $manager->merge($symfonyTag);
        $manager->merge($doctrine2Tag);

        $postAboutPhp = new Post();
        $postAboutPhp->setTitle('Post about php')
        ->setSlug('post-about-php')
        ->setText('The PHP development team would like to announce the immediate availability of PHP 5.3.6.')
        ->addTag($symfonyTag)
        ->addTag($doctrine2Tag)
        ->setAuthor($secondUser)
        ->setPublished(true);
        $manager->persist($postAboutPhp);
        $manager->merge($symfonyTag);
        $manager->merge($doctrine2Tag);

        $manager->flush();

        $this->addReference('post-first', $postFirst);
        $this->addReference('post-about-php', $postAboutPhp);
    }

    /**
     * Get the number for sorting fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
