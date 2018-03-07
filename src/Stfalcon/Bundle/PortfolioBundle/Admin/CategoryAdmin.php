<?php
namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class CategoryAdmin
 */
class CategoryAdmin extends Admin
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
    public function postPersist($post)
    {
        $this->postUpdate($post);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($post)
    {
        $this->configurationPool->getContainer()->get('application_defaultbundle.service.sitemap')->generateSitemap();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\Category',
                'fields' => array(
                    'name' => array(
                        'label' => 'Full Name',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => true,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                    ),
                    'shortName' => array(
                        'label' => 'Short Name',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => true,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                    ),
                    'description' => array(
                        'label' => 'description',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => false,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                        'attr' => array(
                            'class' => 'markitup',
                        ),
                    ),
                    'details' => array(
                        'label' => 'details',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => true,
                            ),
                            'en' => array(
                                'required' => false,
                            ),
                        ),
                        'attr' => array(
                            'class' => 'markitup',
                        ),
                    ),
                    'title' => array(
                        'label' => 'SEO: Title',
                        'locale_options' => array(
                            'ru' => array(
                                'required' => true,
                            ),
                            'en' => array(
                                'required' => true,
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
            ->add('showInServices', null, array('required' => false))
            ->add('showInProjects', null, array('required' => false))
            ->add('cost')
            // @todo сделать сортировку через sortable (по аналогии с проектами)
            ->add('orderNumber');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('name')
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
            ->add('slug')
            ->add('name');
    }
}
