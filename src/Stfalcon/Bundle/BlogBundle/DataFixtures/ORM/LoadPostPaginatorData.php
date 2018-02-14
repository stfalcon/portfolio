<?php

namespace Stfalcon\Bundle\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\BlogBundle\Entity\PostTranslation;
use Faker;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Posts fixtures.
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadPostPaginatorData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Create and load posts fixtures to database.
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $fakerEn = Faker\Factory::create();
        $fakerRu = Faker\Factory::create('ru_RU');

        $phpTag = $this->getReference('tag-php');
        $firstUser = $this->getReference('user-first');
        $locale = 'en';
        $createdAt = new \DateTime();
        for ($i = 1; $i <= 24; ++$i) {
            $post = new Post();
            $post->setSlug($fakerEn->slug());
            if ('en' === $locale) {
                $text = $fakerEn->realText();
                $title = $fakerEn->realText(50);
            } else {
                $text = $fakerRu->realText();
                $title = $fakerRu->realText(50);
            }
            $post->setTitle($title)
                ->setText($text)
                ->addTag($phpTag)
                ->setAuthor($firstUser)
                ->setCreated(clone $createdAt->modify("+$i day"))
                ->setPublished(true)
                ->addTranslation(new PostTranslation($locale, 'title', $title))
                ->addTranslation(new PostTranslation($locale, 'text', $text));

            $manager->persist($post);
            $manager->merge($phpTag);
            $locale = 'en' === $locale ? 'ru' : 'en';
        }

        $manager->flush();
    }

    /**
     * Get the number for sorting fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }

    /**
     * Generate UploadedFile object from local file. For VichUploader
     *
     * @param string $filename
     *
     * @return UploadedFile
     */
    private function generateUploadedFile($filename)
    {
        $fullPath = realpath($this->getKernelDir().'/../web/img/'.$filename);
        if ($fullPath) {
            $tmpFile = tempnam(sys_get_temp_dir(), 'event');
            copy($fullPath, $tmpFile);
            return new UploadedFile($tmpFile,
                $filename, null, null, null, true
            );
        } else {
            return null;
        }
    }

    private function getKernelDir()
    {
        return $this->container->get('kernel')->getRootDir();
    }
}
