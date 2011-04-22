<?php

namespace Application\PortfolioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Application\PortfolioBundle\Entity\Project;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($em)
    {
        // projects
        $preorder = new Project();
        $preorder->setName('preorder.it');
        $preorder->setSlug('preorder-it');
        $preorder->setUrl('http://preorder.it');
        $preorder->setDate(new \DateTime('now'));
        $preorder->setDescription('Press-releases and reviews of the latest electronic novelties: mobile phones, cellphones, smartphones, laptops, tablets, netbooks, gadgets, e-books, photo and video cameras. The possibility to leave a pre-order.');
        $em->persist($preorder);

        $eprice = new Project();
        $eprice->setName('eprice.kz');
        $eprice->setSlug('eprice-kz');
        $eprice->setUrl('http://eprice.kz');
        $eprice->setDate(new \DateTime('now'));
        $eprice->setDescription('Comparison of the prices of mobile phones, computers, monitors, audio and video in Kazakhstan');
        $em->persist($eprice);

        $em->flush();

        $this->addReference('project-preorder', $preorder);
        $this->addReference('project-eprice', $eprice);
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}