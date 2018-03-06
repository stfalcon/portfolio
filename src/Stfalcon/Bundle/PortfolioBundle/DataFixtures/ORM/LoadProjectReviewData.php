<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReview;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Projects fixtures.
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadProjectReviewData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

    public function addProjectReview(ObjectManager $manager, $project, $reviewer, $reviewText, $active)
    {
        $projectReview = (new ProjectReview())
            ->setText($reviewText)
            ->setProject($project)
            ->setReviewer($reviewer)
            ->setActive($active);

        $manager->persist($projectReview);
    }

    /**
     * Create and load projects fixtures to database.
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $novaPochta = $this->getReference('project-preorder');
        $nicUa = $this->getReference('project-eprice');

        $reviwer1 = $this->getReference('reviwer1');
        $reviwer2 = $this->getReference('reviwer2');

        $this->addProjectReview($manager, $novaPochta, $reviwer1, 'Наша компания впервые обратилась к фирме N около полугода назад с задачей разработать продающий сайт, который реально генерирует продажи, а не просто висит мертвым грузом, проедая бюджет. Результаты превзошли ожидания: рост продаж за первый квартал составил +96%, и это в “мертвый сезон”, когда обычно у нас убыток. Планируем заказать фирме N еще несколько сайтов для смежных проектов.', 1);
        $this->addProjectReview($manager, $nicUa, $reviwer2, 'Работаем с студией уже довольно давно. Никаких нареканий, одни положительные впечатления. Будем продолжать сотрудничество.', 1);

        $manager->flush();
    }

    /**
     * Get order number.
     *
     * @return int
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }

    /**
     * Copy file to tmp directory.
     *
     * @param string $originalFileName
     *
     * @return UploadedFile
     */
    protected function copyFile($originalFileName)
    {
        $tempDir = sys_get_temp_dir().'/';

        $absolutePath = dirname(dirname(__FILE__));
        $fs = new Filesystem();
        $tmpFilename = uniqid();
        try {
            $fs->copy($absolutePath.'/Files/'.$originalFileName, $tempDir.$tmpFilename, true);
        } catch (IOException $e) {
            echo 'An error occurred while coping file form '.$absolutePath.'/Files/'.$originalFileName.' to '.$tempDir.'.'.$originalFileName;
        }

        return new UploadedFile($tempDir.$tmpFilename, $originalFileName, null, null, null, true);
    }

    /**
     * Generate UploadedFile object from local file. For VichUploader.
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

            return new UploadedFile($tmpFile, $filename, null, null, null, true);
        }

        return null;
    }

    private function getKernelDir()
    {
        return $this->container->get('kernel')->getRootDir();
    }
}
