<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
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
class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * Create and load projects fixtures to database.
     *
     * @param ObjectManager $manager Entity manager object
     */
    public function load(ObjectManager $manager)
    {
        $files = ['55f18a561ca39.jpeg', '55f18d6a43d1a.jpeg'];

        $devCategory = $this->getReference('category-development');
        $mobileCategory = $this->getReference('category-mobile');
        $webDesign = $this->getReference('category-web');

        $adminUser = $this->getReference('user-admin');
        $firstUser = $this->getReference('user-first');
        $secondUser = $this->getReference('user-second');

        // projects
        $novaPochta = (new Project())
            ->setName('novaposhta.ua')
            ->setSlug('novaposhta-ua')
            ->setUrl('https://new.novaposhta.ua/')
            ->setDate(new \DateTime('now'))
            ->setDescription('
                Nova Poshta, the Ukrainian company, during the past 16 years has become a market leader in the delivery and a driver of 
                its development. The company actively works on the elaboration and improvement of user-friendly online services. 
                Nova Posta and stfalcon.com cooperation was intended to substantially improve the current Personal Dashboard to bring 
                the user experience to a next level.
            ')
            ->setTags('design, HTML markup, development')
            ->setShortDescription('design, HTML markup, development')
            ->setOnFrontPage(1)
            ->setBackgroundColor('#EC584E')
            ->setUseDarkTextColor(false)
            ->setOrderNumber(0)
            ->setShowCase(true)
            ->setCaseContent('TestCase Content')
            ->addCategory($devCategory)
            ->addCategory($webDesign)
            ->addParticipant($adminUser)
            ->addParticipant($secondUser)
            ->setPublished(true)
            ->setImageFile($this->generateUploadedFile('p3.png'));

        $manager->persist($novaPochta);
        $manager->merge($devCategory);
        $manager->merge($webDesign);

        $nicUa = (new Project())
            ->setName('NIC.UA')
            ->setSlug('NIC-UA')
            ->setUrl('https://nic.ua')
            ->setDate(new \DateTime('now'))
            ->setDescription('
                NIC.UA is one of the largest domain name registrar and hosting provider in Ukraine. 
                Previously it was using a monolith server but together with NIC.UA team we have divided this system into back 
                end and front end parts. Now the service is flexible, scalable and has a smart domain search satisfying clients’ 
                needs.
            ')
            ->setTags('design, development')
            ->setShortDescription('design development')
            ->setOnFrontPage(1)
            ->setBackgroundColor('#F4F4F4')
            ->setUseDarkTextColor(true)
            ->setPublished(true)
            ->setOrderNumber(1)
            ->setShowCase(true)
            ->setCaseContent('TestCase Content')
            ->addCategory($devCategory)
            ->addCategory($mobileCategory)
            ->setImageFile($this->generateUploadedFile('p4.png'));

        $manager->persist($nicUa);
        $manager->merge($devCategory);
        $manager->merge($mobileCategory);

        $meinFernbus = (new Project())
            ->setName('MeinFernbus')
            ->setSlug('MeinFernbus')
            ->setUrl('https://meinfernbus.de/')
            ->setDate(new \DateTime('now'))
            ->setDescription('
                MeinFernbus is a leading German supplier of transport services in the area of passenger coaches. 
                The company is considered to be a partner and an innovative progenitor of small and medium tourism businesses 
                in Germany. It functions independently of the big tourist corporations.
            ')
            ->setTags('design, development')
            ->setShortDescription('design development')
            ->setOnFrontPage(1)
            ->setBackgroundColor('#4D9CC9')
            ->setUseDarkTextColor(false)
            ->setPublished(true)
            ->setOrderNumber(2)
            ->setShowCase(true)
            ->setCaseContent('TestCase Content')
            ->addCategory($devCategory)
            ->addCategory($mobileCategory)
            ->setImageFile($this->generateUploadedFile('p6.png'));

        $manager->persist($meinFernbus);
        $manager->merge($devCategory);
        $manager->merge($mobileCategory);

        $manager->flush();

        $this->addReference('project-preorder', $novaPochta);
        $this->addReference('project-eprice', $nicUa);
        $this->addReference('project-meinfernbus', $meinFernbus);

        for ($i = 0; $i < 10; ++$i) {
            $example = (new Project())
                ->setName('example.com_'.$i)
                ->setSlug('example-com_'.$i)
                ->setUrl('http://example.com')
                ->setDate(new \DateTime('now'))
                ->setDescription('As described in RFC 2606, we maintain a number of domains such as 
                    EXAMPLE.COM and EXAMPLE.ORG for documentation purposes. These domains may be used as illustrative 
                    examples in documents without prior coordination with us. They are not available for registration.')
                ->setTags('design, HTML markup, development')
                ->setShortDescription('design, HTML markup, development')
                ->setOnFrontPage(0)
                ->setOrderNumber(2 + $i);
            if ($i % 2) {
                $example->addCategory($devCategory);
            } else {
                $example->addCategory($mobileCategory);
            }
            $example->addParticipant($adminUser)
                ->addParticipant($firstUser)
                ->addParticipant($secondUser)
                ->setPublished(true)
                ->setImageFile($this->copyFile($files[array_rand($files)]));
            $manager->persist($example);
            $manager->merge($devCategory);
            $manager->merge($mobileCategory);
        }
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
            return new UploadedFile($tmpFile, $filename, null, null, null, true);
        }
        return null;
    }

    private function getKernelDir()
    {
        return $this->container->get('kernel')->getRootDir();
    }
}
