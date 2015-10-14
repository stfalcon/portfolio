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
        $userAdmin->setPosition('CEO');
        $userAdmin->setInterests(array('games', 'sportsman'));
        $userAdmin->setDrink('tea');
        $userAdmin->setOrdering(0);
        $userAdmin->setSlug('admin');
        $manager->persist($userAdmin);

        $this->addReference('user-admin', $userAdmin);

        $user = new User();
        $user->setUsername('firstuser');
        $user->setEmail('first-user@stfalcon.com');
        $user->setPlainPassword('qwerty');
        $user->setFirstname('First');
        $user->setLastname('User');
        $user->setPosition('PHP developer');
        $user->setEnabled(true);
        $user->setExpired(false);
        $user->setLocked(false);
        $user->setAvatar($this->copyFile('summer.jpg'));
        $user->setCaricature($this->copyFile('autumn.jpg'));
        $user->setInterests(array('ironman', 'art', 'detectives'));
        $user->setDrink('beer');
        $user->setOrdering(1);
        $user->setSlug('firstuser');
        $manager->persist($user);

        $this->addReference('user-first', $user);

        $user = new User();
        $user->setUsername('seconduser');
        $user->setEmail('second-user@stfalcon.com');
        $user->setPlainPassword('qwerty');
        $user->setFirstname('Second');
        $user->setLastname('User');
        $user->setPosition('Art director, Designer');
        $user->setEnabled(true);
        $user->setExpired(false);
        $user->setLocked(false);
        $user->setAvatar($this->copyFile('summer.jpg'));
        $user->setCaricature($this->copyFile('autumn.jpg'));
        $user->setInterests(array('cyclists', 'art', 'books'));
        $user->setDrink('water');
        $user->setOrdering(2);
        $user->setSlug('seconduser');
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
