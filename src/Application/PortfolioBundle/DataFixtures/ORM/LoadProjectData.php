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
        $preorder->setDescription('Press-releases and reviews of the latest electronic novelties. The possibility to leave a pre-order.');
        $preorder->addCategory($em->merge($this->getReference('category-development')));
        $em->persist($preorder);

        $eprice = new Project();
        $eprice->setName('eprice.kz');
        $eprice->setSlug('eprice-kz');
        $eprice->setUrl('http://eprice.kz');
        $eprice->setDate(new \DateTime('now'));
        $eprice->setDescription('Comparison of the prices of mobile phones, computers, monitors, audio and video in Kazakhstan');
        $eprice->addCategory($em->merge($this->getReference('category-development')));
        $em->persist($eprice);

        $em->flush();

        $this->addReference('project-preorder', $preorder);
        $this->addReference('project-eprice', $eprice);
    }

    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}