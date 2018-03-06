<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReviewer;
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
class LoadProjectReviewerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

    public function addReviewer(ObjectManager $manager, $name, $fileName, $status)
    {
        $reviewer = (new ProjectReviewer())
            ->setName($name)
            ->setPhotoFile($this->generateUploadedFile($fileName))
            ->setStatus($status)
        ;

        $manager->persist($reviewer);

        return $reviewer;
    }

    /**
     * Create and load projects fixtures to database.
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $reviwer1 = $this->addReviewer($manager, 'Andrew Khvetkevich', 'khatkevich.png', 'Nic.ua');
        $this->addReference('reviwer1', $reviwer1);
        $reviwer2 = $this->addReviewer($manager, 'Kirill Podolsky', 'kiril.png', 'менеджер');
        $this->addReference('reviwer2', $reviwer2);

        $manager->flush();
    }

    /**
     * Get order number.
     *
     * @return int
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
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
