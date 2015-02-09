<?php

namespace Stfalcon\RedirectBundle\Service;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Stfalcon\RedirectBundle\Entity\Redirect;
use Stfalcon\RedirectBundle\Entity\Types\RedirectStatusType;

/**
 * Cms block service
 */
class RedirectService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em Entity manager
     *
     * @return RedirectService
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getCollection()
    {
        return $this->loadCollectionFromDb();
    }

    /**
     * Get block content
     *
     * @param string $name
     *
     * @return Redirect|null
     */
    public function getRedirect($name)
    {

        $redirectRepository = $this->getRepository();
        /** @var $redirect Redirect */
        $redirect = $redirectRepository->findOneBy(array('slug' => $name, 'status' => RedirectStatusType::ENABLED));

        return $redirect;
    }

    /**
     * Load route collection from Database
     *
     * @return array
     */
    protected function loadCollectionFromDb()
    {
        $redirectRepository = $this->getRepository();
        $redirects = $redirectRepository->findBy(array('status' => RedirectStatusType::ENABLED));

        return $redirects;
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(Redirect::ENTITY_NAME);
    }
}
