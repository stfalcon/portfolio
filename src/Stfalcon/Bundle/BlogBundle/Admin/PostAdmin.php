<?php
namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('slug')
            ->add('text')
            ->add('tags', 'tags')
            ->add('author', null, array(
                    'required' => true,
                    'empty_value' => 'Choose an user'
                )
            )
        ->add('created', 'date', array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy'
                ))
        ->add('published', null, array('required' => false));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('title')
            ->add('created');
    }
}