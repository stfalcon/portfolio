<?php

namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class ProjectReviewerAdmin
 */
class ProjectReviewerAdmin extends Admin
{
    /**
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->with('Переводы')
                ->add(
                    'translations',
                    'a2lix_translations_gedmo',
                    [
                        'translatable_class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\ProjectReviewer',
                        'fields' => [
                            'name' => [
                                'label' => 'имя',
                                'locale_options' => [
                                    'ru' => ['required' => true],
                                    'en' => ['required' => false],
                                ],
                            ],
                        ],
                    ]
                )
            ->end()
            ->with('Основное')
                ->add('photoFile', 'file')
                ->add('projectReviews')
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('id')
            ->add('name');
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->addIdentifier('name');
    }
}
