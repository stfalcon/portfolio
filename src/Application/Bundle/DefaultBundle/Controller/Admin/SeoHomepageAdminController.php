<?php

namespace Application\Bundle\DefaultBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use A2lix\TranslationFormBundle\Annotation\GedmoTranslation;

/**
 * SeoHomepageAdminController
 */
class SeoHomepageAdminController extends CRUDController
{
    /**
     * {@inheritdoc}
     *
     * @GedmoTranslation
     */
    public function createAction()
    {
        return parent::createAction();
    }

    /**
     * {@inheritdoc}
     *
     * @GedmoTranslation
     */
    public function editAction($id = null)
    {
        return parent::editAction($id);
    }
}
