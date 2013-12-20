<?php
namespace Application\Bundle\BlogBundle\Admin;

use Stfalcon\Bundle\BlogBundle\Admin\PostAdmin as BaseAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends BaseAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
        parent::configureFormFields($formMapper);
        $formMapper->add('author', null, array('data' => $user));
    }
}
