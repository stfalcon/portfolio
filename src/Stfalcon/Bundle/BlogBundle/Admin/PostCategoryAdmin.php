<?php

namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Stfalcon\Bundle\BlogBundle\Entity\Post;
use Stfalcon\Bundle\BlogBundle\Entity\PostCategory;

/**
 * Class PostCategoryAdmin.
 */
class PostCategoryAdmin extends Admin
{
    private $prevPosts = null;

    /**
     * @param PostCategory $object
     *
     * @return mixed|void
     */
    public function preUpdate($object)
    {
        /** @var Post $post */
        foreach ($this->prevPosts as $post) {
            if (!$object->getPosts()->contains($post)) {
                $post->setCategory(null);
            }
        }
        /** @var Post $post */
        foreach ($object->getPosts() as $post) {
            $post->setCategory($object);
        }
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var PostCategory $postCategory */
        $postCategory = $this->getSubject();
        $objectId = $postCategory->getId();
        if ($objectId) {
            $this->prevPosts = clone $postCategory->getPosts();
        }

        $formMapper
            ->add(
                'translations',
                'a2lix_translations_gedmo',
                [
                    'translatable_class' => 'Stfalcon\Bundle\BlogBundle\Entity\PostCategory',
                    'fields' => [
                        'name' => [
                            'label' => 'Name',
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
                    'label' => 'Перевод',
                ]
            )
            ->add('slug', null, [ 'disabled' => \is_null($objectId)])
            ->add('posts', null, [
                'help' => \is_null($objectId) ? 'добавлять посты можно только после создания категории' :
                    'выбирете посты для категори',
                'disabled' => \is_null($objectId),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('posts')
            ->add('_action', 'actions', [
                'label' => 'Действия',
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper->add('name');
    }
}
