<?php

namespace Application\PortfolioBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadCategoriesAndProjectsData implements FixtureInterface
{
    public function load($em)
    {
        // projects
        $preorder = new \Application\PortfolioBundle\Entity\Project();
        $preorder->setName('preorder.it');
        $preorder->setDescription('Press-releases and reviews of the latest electronic novelties: mobile phones, cellphones, smartphones, laptops, tablets, netbooks, gadgets, e-books, photo and video cameras. The possibility to leave a pre-order.');
        $em->persist($preorder);

        $eprice = new \Application\PortfolioBundle\Entity\Project();
        $eprice->setName('eprice.kz');
        $eprice->setDescription('Comparison of the prices of mobile phones, computers, monitors, audio and video in Kazakhstan');
        $em->persist($eprice);

        // categories
        $development = new \Application\PortfolioBundle\Entity\Category();
        $development->setName('Web Development');
        $development->setDescription('In work we use PHP (Zend Framework, Doctrine, Smarty, PEAR), JavaScript (jQuery, YUI, MooTools), SQL (MySQL, PgSQL), HTML/XHTML, CSS, bugtrackers and source control systems.');
        $development->addProject($preorder);
        $development->addProject($eprice);
        $em->persist($development);

        $em->flush();
    }
}
