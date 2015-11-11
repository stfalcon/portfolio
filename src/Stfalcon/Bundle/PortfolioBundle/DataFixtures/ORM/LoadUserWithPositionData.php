<?php

namespace Stfalcon\Bundle\PortfolioBundle\DataFixtures\ORM;

use Application\Bundle\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Stfalcon\Bundle\PortfolioBundle\Entity\Project;
use Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPosition;

/**
 * LoadUserWithPositionData class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class LoadUserWithPositionData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $userAdmin */
        /** @var User $userFirst */
        /** @var User $userSecond */
        $userAdmin  = $this->getReference('user-admin');
        $userFirst  = $this->getReference('user-first');
        $userSecond = $this->getReference('user-second');

        /** @var Project $projectPreOrder */
        /** @var Project $projectEPrice */
        $projectPreOrder = $this->getReference('project-preorder');
        $projectEPrice = $this->getReference('project-eprice');

        $userWithPosition = (new UserWithPosition())
            ->setUser($userAdmin)
            ->setProject($projectPreOrder)
            ->setPositions('CEO, Admin');
        $manager->persist($userWithPosition);

        $userWithPosition = (new UserWithPosition())
            ->setUser($userFirst)
            ->setProject($projectPreOrder)
            ->setPositions('User, First');
        $manager->persist($userWithPosition);

        $userWithPosition = (new UserWithPosition())
            ->setUser($userSecond)
            ->setProject($projectPreOrder)
            ->setPositions('User, Second');
        $manager->persist($userWithPosition);

        $userWithPosition = (new UserWithPosition())
            ->setUser($userAdmin)
            ->setProject($projectEPrice)
            ->setPositions('Admin');
        $manager->persist($userWithPosition);

        $userWithPosition = (new UserWithPosition())
            ->setUser($userFirst)
            ->setProject($projectEPrice)
            ->setPositions('First');
        $manager->persist($userWithPosition);

        $userWithPosition = (new UserWithPosition())
            ->setUser($userSecond)
            ->setProject($projectEPrice)
            ->setPositions('Second');
        $manager->persist($userWithPosition);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}
