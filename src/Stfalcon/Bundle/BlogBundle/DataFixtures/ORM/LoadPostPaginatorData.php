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
        $locale = 'en';
        $createdAt = new \DateTime();
        for ($i=1; $i<=24; $i++) {
            $post = new Post();
            $post->setSlug('post-for-paginator-'.$i);
            if ($locale === 'en') {
                $text = 'Generally this bundle is based on Knp Pager component. This component introduces a different way for pagination handling. You can read more about the internal logic on the given documentation link.' . $i;
                $title = 'Post for paginator #' . $i;
            } else {
                $text = 'Это тестовий текст для поста. 12 ноября в Киеве, пройдет конференция Zend Framework Day, посвященная популярному PHP фреймворку Zend Framework. Наша студия выступает партнером конференции.

Среди докладчиков будут непосредственные разработчики компонентов Zend Framework. Событие соберет лучших PHP и Zend Framework специалистов из Украины, России, Белоруссии и других стран СНГ..' . $i;
                $title = 'Тест Пост для пагинатора #' . $i;
            }
            $post->setTitle($title);
            $post->setText($text);
            $post->addTag($phpTag);
            $post->setAuthor($firstUser);
            $post->setCreated(clone $createdAt->modify("+$i day"));
            $post->setPublished(true);
            $post->addTranslation(new PostTranslation($locale, 'title', $locale.' '.$title));
            $post->addTranslation(new PostTranslation($locale, 'text', $locale.' '.$text));

            $manager->persist($post);
            $manager->merge($phpTag);
            $locale = $locale === 'en' ? 'ru' : 'en';
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