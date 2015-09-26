<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Projects fixtures
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Create and load projects fixtures to database
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $files = array(
            '55f18a561ca39.jpeg',
            '55f18d6a43d1a.jpeg'
        );
        $webCategory = $this->getReference('category-development');
        $mobileCategory = $this->getReference('mobile-development');

        $adminUser = $this->getReference('user-admin');
        $firstUser = $this->getReference('user-first');
        $secondUser = $this->getReference('user-second');

        // projects
        $preOrder = new Project();
        $preOrder->setName('preorder.it');
        $preOrder->setSlug('preorder-it');
        $preOrder->setUrl('http://preorder.it');
        $preOrder->setDate(new \DateTime('now'));
        $preOrder->setDescription('Press-releases and reviews of the latest electronic novelties. The possibility to leave a pre-order.');
        $preOrder->setTags('design, HTML markup, development');
        $preOrder->setShortDescription('design, HTML markup, development');
        $preOrder->setOnFrontPage(1);
        $preOrder->setOrdernum(0);
        $preOrder->addCategory($webCategory);
        $preOrder->addParticipant($adminUser);
        $preOrder->addParticipant($secondUser);
        $preOrder->setPublished(true);
        $preOrder->setImageFile($this->copyFile($files[array_rand($files)]));
        $manager->persist($preOrder);
        $manager->merge($webCategory);

        $ePrice = new Project();
        $ePrice->setName('eprice.kz');
        $ePrice->setSlug('eprice-kz');
        $ePrice->setUrl('http://eprice.kz');
        $ePrice->setDate(new \DateTime('now'));
        $ePrice->setDescription('Comparison of the prices of mobile phones, computers, monitors, audio and video in Kazakhstan');
        $ePrice->setTags('design');
        $ePrice->setShortDescription('design');
        $ePrice->setOnFrontPage(1);
        $ePrice->setPublished(true);
        $ePrice->setOrdernum(1);
        $ePrice->addCategory($webCategory);
        $ePrice->addCategory($mobileCategory);
//        $ePrice->addParticipant($adminUser);
//        $ePrice->addParticipant($firstUser);
        $ePrice->setImageFile($this->copyFile($files[array_rand($files)]));
        $manager->persist($ePrice);
        $manager->merge($webCategory);

        $manager->flush();

        $this->addReference('project-preorder', $preOrder);
        $this->addReference('project-eprice', $ePrice);

        for ($i = 0; $i < 20; $i++) {
            $example = new Project();
            $example->setName('example.com_' . $i);
            $example->setSlug('example-com_' . $i);
            $example->setUrl('http://example.com');
            $example->setDate(new \DateTime('now'));
            $example->setDescription('As described in RFC 2606, we maintain a number of domains such as EXAMPLE.COM and EXAMPLE.ORG for documentation purposes. These domains may be used as illustrative examples in documents without prior coordination with us. They are not available for registration.');
            $example->setTags('design, HTML markup, development');
            $example->setShortDescription('design, HTML markup, development');
            $example->setOnFrontPage(0);
            $example->setOrdernum(2 + $i);
            if ($i % 2){
                $example->addCategory($webCategory);
            } else {
                $example->addCategory($mobileCategory);
            }
            $example->addParticipant($adminUser);
            $example->addParticipant($firstUser);
            $example->addParticipant($secondUser);
            $example->setPublished(true);
            $example->setImageFile($this->copyFile($files[array_rand($files)]));
            $manager->persist($example);
            $manager->merge($webCategory);
        }
        $manager->flush();
    }

    /**
     * Get order number
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }

    /**
     * Copy file to tmp directory
     *
     * @param string $originalFileName
     *
     * @return UploadedFile
     */
    protected function copyFile($originalFileName)
    {
        $tempDir = sys_get_temp_dir() . '/';

        $absolutePath = dirname(dirname(__FILE__));
        $fs = new Filesystem();
        $tmpFilename = uniqid();
        try {
            $fs->copy($absolutePath .'/Files/'. $originalFileName, $tempDir . $tmpFilename, true);
        } catch (IOException $e) {
            echo "An error occurred while coping file form " . $absolutePath . '/Files/' . $originalFileName . ' to ' . $tempDir . '.' . $originalFileName;
        }

        return new UploadedFile($tempDir . $tmpFilename, $originalFileName, null, null, null, true);
    }
}