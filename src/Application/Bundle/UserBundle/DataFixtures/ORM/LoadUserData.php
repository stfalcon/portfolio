<?php

namespace Application\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Application\Bundle\UserBundle\Entity\User;

/**
 * Users fixtures
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Create and load user fixtures to database
     *
     * @param ObjectManager $manager Entity manager object
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('Administrator');
        $userAdmin->setEmail('admin@stfalcon.com');
        $userAdmin->setPlainPassword('qwerty');
        $userAdmin->setRoles(array('ROLE_SUPER_ADMIN'));
        $userAdmin->setEnabled(true);
        $userAdmin->setExpired(false);
        $userAdmin->setLocked(false);
        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference('user-admin', $userAdmin);
    }

    /**
     * Get the number for sorting fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }

}
