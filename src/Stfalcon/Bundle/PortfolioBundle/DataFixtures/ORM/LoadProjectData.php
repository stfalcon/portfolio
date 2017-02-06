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
    private $files = [
        '55f18a561ca39.jpeg',
        '55f18d6a43d1a.jpeg',
    ];

    /**
     * @param $number
     * @param $name
     * @param $url
     * @param array         $categories
     * @param array         $users
     * @param ObjectManager $manager
     */
    public function createProject($number, $name, $url, $categories, $users, $manager, $referenceName = '')
    {
        $project = new Project();
        $project->setName($name);
        $project->setSlug($name);
        $project->setUrl($url);
        $project->setDate(new \DateTime('now'));
        $project->setDescription('Some test description');
        $project->setTags('design, HTML markup, development');
        $project->setShortDescription('design, HTML markup, development');
        $project->setOnFrontPage(1);
        $project->setOrdernum($number);
        $i = 0;
        while (isset($categories[$i])) {
            $project->addCategory($categories[$i]);
            $manager->merge($categories[$i]);
            $i++;
        }
        $i = 0;
        while (isset($users[$i])) {
            $project->addParticipant($users[$i]);
            $i++;
        }
        $project->setPublished(true);
        $project->setImageFile($this->copyFile($this->files[array_rand($this->files)]));
        $manager->persist($project);
        if (empty($referenceName)) {
            $this->addReference('project-' . $number, $project);
        } else {
            $this->addReference($referenceName, $project);
        }
    }
    /**
     * Create and load projects fixtures to database
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $webCategory = $this->getReference('web-development');
        $mobileCategory = $this->getReference('mobile-development');
        $webDesign = $this->getReference('web-design');

        $adminUser = $this->getReference('user-admin');
        $firstUser = $this->getReference('user-first');
        $secondUser = $this->getReference('user-second');

        $this->createProject(0, 'meinfernbus-de', 'http://meinfernbus.de/', [$webCategory], [$adminUser], $manager);
        $this->createProject(1, 'keepsnap-com', 'https://keepsnap.com/', [$webCategory], [$firstUser], $manager);
        $this->createProject(2, 'naberezhny-kvartal-crm', '', [$webDesign], [$adminUser], $manager);
        $this->createProject(3, 'uaroads-com', 'http://uaroads.com/', [$webCategory], [$secondUser], $manager);

        $this->createProject(4, 'preorder.it', 'http://preorder.it', [$webCategory], [$adminUser, $secondUser], $manager, 'project-preorder');
        $this->createProject(5, 'eprice.kz', 'http://eprice.kz', [$webCategory, $mobileCategory], [], $manager, 'project-eprice');

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
            $example->setOrdernum(5 + $i);
            if ($i % 2){
                $example->addCategory($webCategory);
            } else {
                $example->addCategory($mobileCategory);
            }
            $example->addParticipant($adminUser);
            $example->addParticipant($firstUser);
            $example->addParticipant($secondUser);
            $example->setPublished(true);
            $example->setImageFile($this->copyFile($this->files[array_rand($this->files)]));
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