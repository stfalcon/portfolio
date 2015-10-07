<?php

namespace Stfalcon\Bundle\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * CommentAdmin
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class CommentAdmin extends Admin
{
    /**
     * {@inheritDoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('email')
            ->add('message')
            ->add('parent')
            ->add('language', 'choice', [
                'choices' => [
                    'ru' => 'Ru',
                    'en' => 'En',
                ]
            ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('email')
            ->add('message')
            ->add('createdAt')
            ->add('parent')
            ->add('language');
    }
}
