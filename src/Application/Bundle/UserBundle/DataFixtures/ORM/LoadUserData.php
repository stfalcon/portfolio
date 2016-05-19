<?php

namespace Application\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Application\Bundle\UserBundle\Entity\User;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@stfalcon.com');
        $userAdmin->setPlainPassword('qwerty');
        $userAdmin->setRoles(array('ROLE_SUPER_ADMIN'));
        $userAdmin->setEnabled(true);
        $userAdmin->setExpired(false);
        $userAdmin->setLocked(false);
        $userAdmin->setAvatar($this->copyFile('summer.jpg'));
        $userAdmin->setCaricature($this->copyFile('autumn.jpg'));
        $userAdmin->setFirstname('Admin');
        $userAdmin->setLastname('User');
        $userAdmin->setDescription(<<<TEXT
Nulla porttitor accumsan tincidunt. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Donec sollicitudin
molestie malesuada. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Vestibulum ante ipsum primis in
faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper
sit amet ligula.
TEXT
);
        $userAdmin->setPosition('CEO');
        $userAdmin->setInterests(array('games', 'sportsman'));
        $userAdmin->setDrink('tea');
        $userAdmin->setOrdering(0);
        $manager->persist($userAdmin);

        $this->addReference('user-admin', $userAdmin);

        $user = new User();
        $user->setUsername('firstuser');
        $user->setEmail('first-user@stfalcon.com');
        $user->setPlainPassword('qwerty');
        $user->setFirstname('First');
        $user->setLastname('User');
        $user->setDescription(<<<TEXT
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eget tortor risus. Proin eget tortor risus. Curabitur
aliquet quam id dui posuere blandit. Sed porttitor lectus nibh.
TEXT
);
        $user->setPosition('PHP developer');
        $user->setEnabled(true);
        $user->setExpired(false);
        $user->setLocked(false);
        $user->setAvatar($this->copyFile('summer.jpg'));
        $user->setCaricature($this->copyFile('autumn.jpg'));
        $user->setInterests(array('ironman', 'art', 'detectives'));
        $user->setDrink('beer');
        $user->setOrdering(1);
        $manager->persist($user);

        $this->addReference('user-first', $user);

        $user = new User();
        $user->setUsername('seconduser');
        $user->setEmail('second-user@stfalcon.com');
        $user->setPlainPassword('qwerty');
        $user->setFirstname('Second');
        $user->setLastname('User');
        $user->setDescription(<<<TEXT
Vivamus suscipit tortor eget felis porttitor volutpat. Pellentesque in ipsum id orci porta dapibus. Vestibulum ac diam
sit amet quam vehicula elementum sed sit amet dui. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.
Proin eget tortor risus.
TEXT
);
        $user->setPosition('Art director, Designer');
        $user->setEnabled(true);
        $user->setExpired(false);
        $user->setLocked(false);
        $user->setAvatar($this->copyFile('summer.jpg'));
        $user->setCaricature($this->copyFile('autumn.jpg'));
        $user->setInterests(array('cyclists', 'art', 'books'));
        $user->setDrink('water');
        $user->setOrdering(2);
        $manager->persist($user);

        $this->addReference('user-second', $user);

        $manager->flush();
    }

    /**
     * Get the number for sorting fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
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
