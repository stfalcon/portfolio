<?php

namespace Application\Bundle\DefaultBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class SeoHomepageAdmin
 */
class SeoHomepageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                [
                    'translatable_class' => 'Application\Bundle\DefaultBundle\Entity\SeoHomepage',
                    'fields' => [
                        'title' => [
                            'label' => 'Meta title',
                            'locale_options' => [
                                'ru' => [
                                    'required' => true,
                                ],
                                'en' => [
                                    'required' => true,
                                ],
                            ],
                        ],
                        'keywords' => [
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
                        'description' => [
                            'label' => 'Meta description',
                            'locale_options' => [
                                'ru' => [
                                    'required' => true,
                                ],
                                'en' => [
                                    'required' => true,
                                ],
                            ],
                        ],
                        'ogTitle' => [
                            'label' => 'og:title',
                            'locale_options' => [
                                'ru' => [
                                    'required' => true,
                                ],
                                'en' => [
                                    'required' => true,
                                ],
                            ],
                        ],
                        'ogDescription' => [
                            'label' => 'og:description',
                            'locale_options' => [
                                'ru' => [
                                    'required' => true,
                                ],
                                'en' => [
                                    'required' => true,
                                ],
                            ],
                        ],
                    ],
                    'label' => 'Перевод',
                ]
            )
            ->add('ogImageFile', 'file', [
                'label' => 'og:image',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->add(
                '_action',
                'actions',
                [
                    'label' => 'Действия',
                    'actions' => [
                        'edit' => [],
                    ],
                ]
            );
    }
}
