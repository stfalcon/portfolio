<?php

namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * UserWithPositionAdmin class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class UserWithPositionAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('user')
            ->add('translations', 'a2lix_translations_gedmo', [
                    'translatable_class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\UserWithPosition',
                    'fields'             => [
                        'positions' => [
                            'label'          => 'Positions',
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
                ]
            );
    }
}
