<?php
namespace Stfalcon\Bundle\PortfolioBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;

/**
 * Class LandingAdmin
 */
class LandingAdmin extends Admin
{
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
                                    'required' => true
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            )
                        ),
                        'text' => array(
                            'label' => 'text',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => true
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            )
                        ),
                        'metaTitle' => array(
                            'label' => 'SEO: Title',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => true
                                ),
                                'en' => array(
                                    'required' => true
                                )
                            ),
                            'required' => false
                        ),
                        'metaDescription' => array(
                            'label' => 'SEO: Meta Description',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => false
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            ),
                            'required' => false
                        ),
                        'metaKeywords' => array(
                            'label' => 'SEO: Meta Keywords',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => false
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            ),
                            'required' => false
                        ),
                    ),
                    'label' => 'Перевод'
                )
            )
            ->add('slug', 'choice', [
                'choices' => array(
                    'mobile-app-design' => 'mobile-app-design',
                    'responsive-design' => 'responsive-design',
                    'ui-design' => 'ui-design',
                    'ember-js' => 'ember-js',
                    'silex' => 'silex',
                    'sylius' => 'sylius'
                )
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug');
    }
}
