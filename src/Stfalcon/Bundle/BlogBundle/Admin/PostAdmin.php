<?php
namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class PostAdmin
 */
class PostAdmin extends Admin
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

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'a2lix_translations_gedmo', array(
                    'translatable_class' => 'Stfalcon\Bundle\BlogBundle\Entity\Post',
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
                            ),
                            'attr' => array(
                                'class' => 'markitup'
                            )
                        ),
                        'metaKeywords' => array(
                            'label' => 'Meta keywords',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => false
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            )
                        ),
                        'metaDescription' => array(
                            'label' => 'Meta description',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => false
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            )
                        ),
                        'metaTitle' => array(
                            'label' => 'Meta title',
                            'locale_options' => array(
                                'ru' => array(
                                    'required' => false
                                ),
                                'en' => array(
                                    'required' => false
                                )
                            )
                        )
                    ),
                    'label' => 'Перевод'
                )
            )
            ->add('slug')
            ->add('tags', null)
            ->add('image', null, ['required' => false])
            ->add('author', null, array(
                'required' => true,
                'placeholder' => 'Choose an user',
            ))
            ->add('created', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('published', null, array('required' => false));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('slug')
            ->add('title')
            ->add('created')
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
            ->add('title');
    }
}
