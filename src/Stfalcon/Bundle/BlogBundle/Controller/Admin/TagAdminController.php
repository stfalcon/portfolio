<?php

namespace Stfalcon\Bundle\BlogBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use A2lix\TranslationFormBundle\Annotation\GedmoTranslation;

/**
 * TagAdminController
 *
 * @author Artem Genvald <genvaldartem@gmail.com>
 */
class TagAdminController extends CRUDController
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
