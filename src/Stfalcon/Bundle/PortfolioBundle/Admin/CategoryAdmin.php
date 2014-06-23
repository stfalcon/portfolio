<?php
namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class CategoryAdmin
 */
class CategoryAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'a2lix_translations_gedmo', array(
                    'translatable_class' => 'Stfalcon\Bundle\PortfolioBundle\Entity\Category',
                    'fields' => array(
                        'name' => array(
                            'label' => 'name',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => true
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            )
                        ),
                        'description' => array(
                            'label' => 'description',
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
            )
            ->add('slug')
            ->add('cost')
            // @todo сделать сортировку через sortable (по аналогии с проектами)
            ->add('ordernum');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('name');
    }
}