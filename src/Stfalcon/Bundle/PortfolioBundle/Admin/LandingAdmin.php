<?php
namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class LandingAdmin
 */
class LandingAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'DESC',
    ];

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\Landing',
                'fields' => array(
                    'title' => array(
                        'label' => 'title',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => true,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                    ),
                    'text' => array(
                        'label' => 'text',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => true,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                    ),
                    'metaTitle' => array(
                        'label' => 'SEO: Title',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => true,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                        'required' => false,
                    ),
                    'metaDescription' => array(
                        'label' => 'SEO: Meta Description',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => false,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                        'required' => false,
                    ),
                    'metaKeywords' => array(
                        'label' => 'SEO: Meta Keywords',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => false,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                        'required' => false,
                    ),
                ),
                'label' => 'Перевод',
            ))
            ->add('slug')
            ->add('projects')
            ->add('posts')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('slug')
            ->add('_action', 'actions', [
                'label' => 'Действия',
                'actions' => [
                    'edit'   => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('title')
            ->add('slug');
    }
}
