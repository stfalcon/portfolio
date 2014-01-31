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
     * @param Doctrine\ORM\EntityManager $manager Entity manager object
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        // posts
        $postfirst = new Post();
        $postfirst->setTitle('My first post');
        $postfirst->setSlug('my-first-post');
        $postfirst->setText('In work we use Symfony2.<!--more-->And text after cut');
        $postfirst->setTags(array(
            $manager->merge($this->getReference('tag-symfony2')),
            $manager->merge($this->getReference('tag-doctrine2')),
        ));
        $manager->persist($postfirst);

        $postaboutphp = new Post();
        $postaboutphp->setTitle('Post about php');
        $postaboutphp->setSlug('post-about-php');
        $postaboutphp->setText('The PHP development team would like to announce the immediate availability of PHP 5.3.6.');
        $postaboutphp->setTags(array(
            $manager->merge($this->getReference('tag-symfony2')),
            $manager->merge($this->getReference('tag-php')))
        );
        $manager->persist($postaboutphp);

        $manager->flush();

        $this->addReference('post-first', $postfirst);
        $this->addReference('post-about-php', $postaboutphp);
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