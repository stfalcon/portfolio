<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\BlogBundle\Entity\PostTranslation;

/**
 * Posts fixtures
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadPostPaginatorData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Create and load posts fixtures to database
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $phpTag = $this->getReference('tag-php');
        $firstUser = $this->getReference('user-first');

        $createdAt = new \DateTime();
        for ($i=1; $i<=12; $i++) {
            $post = new Post();
            $title = 'Post for paginator #' . $i;
            $post->setTitle($title);
            $post->setSlug('post-for-paginator-'.$i);
            $text = 'Generally this bundle is based on Knp Pager component. This component introduces a different way for pagination handling. You can read more about the internal logic on the given documentation link.' . $i;
            $post->setText($text);
            $post->addTag($phpTag);
            $post->setAuthor($firstUser);
            $post->setCreated(clone $createdAt->modify("+$i day"));
            $post->setPublished(true);
            $post->addTranslation(new PostTranslation('en', 'title', 'EN ' . $title));
            $post->addTranslation(new PostTranslation('en', 'text', 'EN ' . $text));

            $manager->persist($post);
            $manager->merge($phpTag);
        }

        $manager->flush();
    }

    /**
     * Get the number for sorting fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }

}