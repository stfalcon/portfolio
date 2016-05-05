<?php

namespace Application\Bundle\DefaultBundle\Service\SeoAlternate;

use Application\Bundle\UserBundle\Entity\User;

/**
 * UserAlternateService class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class UserAlternateService extends AbstractSeoAlternateService
{
    /**
     * Get identifier
     *
     * @param User $user User
     *
     * @return string
     */
    public function getIdentifier($user)
    {
        return $user->getUsername();
    }

    /**
     * Get translation
     *
     * @param string $username Username
     * @param string $locale   Locale
     *
     * @return User|null
     */
    public function getTranslation($username, $locale)
    {
        return $this->em->getRepository('ApplicationUserBundle:User')->findUserByUsernameAndLocale($username, $locale);
    }
}
