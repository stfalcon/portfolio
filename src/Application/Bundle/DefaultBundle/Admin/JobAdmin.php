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
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                [
                    'translatable_class' => 'Application\Bundle\DefaultBundle\Entity\Job',
                    'fields' => [
                        'title' => [
                            'label' => 'Title',
                            'locale_options' => [
                                'ru' => [
                                    'required' => true,
                                ],
                                'en' => [
                                    'required' => false,
                                ],
                            ],
                        ],
                        'description' => [
                            'label' => 'Описание',
                            'locale_options' => [
                                'ru' => [
                                    'required' => true,
                                ],
                                'en' => [
                                    'required' => false,
                                ],
                            ],
                        ],
                        'metaTitle' => [
                            'label' => 'Meta title',
                            'locale_options' => [
                                'ru' => [
                                    'required' => true,
                                ],
                                'en' => [
                                    'required' => false,
                                ],
                            ],
                        ],
                        'metaKeywords' => [
                            'label' => 'Meta keywords',
                            'locale_options' => [
                                'ru' => [
                                    'required' => false,
                                ],
                                'en' => [
                                    'required' => false,
                                ],
                            ],
                        ],
                        'metaDescription' => [
                            'label' => 'Meta description',
                            'locale_options' => [
                                'ru' => [
                                    'required' => false,
                                ],
                                'en' => [
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->add('slug')
            ->add('active')
            ->add('sortOrder')
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
            ->add('createdAt')
            ->add('updatedAt')
            ->addIdentifier('sortOrder')
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
            ->add('sortOrder')
        ;
    }
}
