<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\BlogBundle\Entity\Comment;
use Stfalcon\Bundle\BlogBundle\Entity\Post;

/**
 * LoadCommentData
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var Post $postFirst */
        /** @var Post $postAboutPhp */
        $postFirst = $this->getReference('post-first');
        $postAboutPhp = $this->getReference('post-about-php');

        // Post first
        $comment1 = (new Comment())
            ->setName('New user #1')
            ->setEmail('new1@user.ua')
            ->setMessage('Some text message!')
            ->setPost($postFirst)
            ->setLanguage('ru');
        $manager->persist($comment1);

        $comment2 = (new Comment())
            ->setName('New user #2')
            ->setEmail('new2@user.ua')
            ->setMessage('New comment message')
            ->setPost($postFirst)
            ->addChild($comment1)
            ->setLanguage('ru');
        $manager->persist($comment2);

        $comment3 = (new Comment())
            ->setName('New user #3')
            ->setEmail('new3@user.ua')
            ->setMessage('New user #3 comment message')
            ->setPost($postFirst)
            ->setLanguage('ru');
        $manager->persist($comment3);

        $comment4 = (new Comment())
            ->setName('New user #4')
            ->setEmail('new4@user.ua')
            ->setMessage('In work we use Symfony2')
            ->setPost($postFirst)
            ->addChild($comment3)
            ->addChild($comment2)
            ->setLanguage('ru');
        $manager->persist($comment4);

        // Post about php
        $comment5 = (new Comment())
            ->setName('New user #5')
            ->setEmail('new5@user.ua')
            ->setMessage('Comment short message')
            ->setPost($postAboutPhp)
            ->setLanguage('en');
        $manager->persist($comment5);

        $comment6 = (new Comment())
            ->setName('New user #6')
            ->setEmail('new6@user.ua')
            ->setMessage('Comment long message. Comment long message. Comment long message.')
            ->setPost($postAboutPhp)
            ->setLanguage('en');
        $manager->persist($comment6);

        $comment7 = (new Comment())
            ->setName('New user #7')
            ->setEmail('new7@user.ua')
            ->setMessage('Some comment message. Some comment message. Some comment message.')
            ->setPost($postAboutPhp)
            ->setLanguage('en');
        $manager->persist($comment7);

        $comment8 = (new Comment())
            ->setName('New user #8')
            ->setEmail('new8@user.ua')
            ->setMessage('In work we use Symfony2. In work we use Symfony2. In work we use Symfony2.')
            ->setPost($postAboutPhp)
            ->addChild($comment7)
            ->addChild($comment6)
            ->setLanguage('en');
        $manager->persist($comment8);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
