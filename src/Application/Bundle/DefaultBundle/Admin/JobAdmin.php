<?php

namespace Application\Bundle\DefaultBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class JobAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('slug')
            ->add('active')
            ->add('description', null, ['attr' => ['class' => 'markitup']])
            ->add('metaKeywords', null, ['label' => 'Meta keywords', 'required' => false])
            ->add('metaDescription', null, ['label' => 'Meta description', 'required' => false])
            ->add('metaTitle', null, ['label' => 'Meta title', 'required' => false])
            ->add('tags', null)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->addIdentifier('slug')
            ->add('active')
            ->add('created')
            ->add('_action', 'actions', [
                'label' => 'Действия',
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('title')
            ->add('slug')
            ->add('active')
        ;
    }
}
