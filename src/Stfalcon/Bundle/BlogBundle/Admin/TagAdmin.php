<?php
namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TagAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'translations',
                'a2lix_translations',
                array(
                    'required' => true,
                    'fields' => array(
                        'text' => array(
                            'label' => 'text',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => true
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            )
                        ),
                    ),
                    'label' => 'Перевод'
                )
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('text')
            ->add('id')
        ;
    }
}