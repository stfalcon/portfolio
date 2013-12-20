<?php
namespace Application\Bundle\PortfolioBundle\Admin;

use Stfalcon\Bundle\PortfolioBundle\Admin\ProjectAdmin as BaseAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ProjectAdmin extends BaseAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper->add('participants', 'entity', array(
            'required' => false,
            'label' => 'Учасники',
            'class' => 'Application\Bundle\UserBundle\Entity\User',
            'multiple' => true
        ))
        ->with('Media')
            ->add('media', 'sonata_type_collection', array(
                'cascade_validation' => true,
            ), array(
                'edit'              => 'inline',
                'inline'            => 'table',
                'sortable'          => 'position',
                'link_parameters'   => array('context' => 'default'),
                'admin_code'        => 'sonata.media.admin.gallery_has_media'
            ))
        ->end();
    }
}
