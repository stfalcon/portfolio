<?php

namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class PostCategoryAdmin.
 */
class PostCategoryAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                [
                    'translatable_class' => 'Stfalcon\Bundle\BlogBundle\Entity\PostCategory',
                    'fields' => [
                        'name' => [
                            'label' => 'Name',
                            'locale_options' => [
                                'ru' => [
                                    'required' => true,
                                ],
                                'en' => [
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],
                    'label' => 'Перевод',
                ]
            )
            ->add('posts')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('posts')
            ->add('_action', 'actions', [
                'label' => 'Действия',
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper->add('name');
    }
}
