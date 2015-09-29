<?php
namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class TagAdmin
 */
class TagAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('translations', 'a2lix_translations_gedmo', [
                    'translatable_class' => 'Stfalcon\Bundle\BlogBundle\Entity\Tag',
                    'fields'             => [
                        'text' => [
                            'label'          => 'Text',
                            'locale_options' => [
                                'ru' => ['required' => true],
                                'en' => ['required' => false],
                            ],
                        ],
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('text')
            ->add('id');
    }

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
        $this->configurationPool
             ->getContainer()
             ->get('application_defaultbundle.service.sitemap')
             ->generateSitemap();
    }
}
