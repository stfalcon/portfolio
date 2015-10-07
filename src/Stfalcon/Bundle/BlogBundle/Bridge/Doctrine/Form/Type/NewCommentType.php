<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * NewCommentType
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class NewCommentType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('message', 'textarea')
            ->add('language', 'hidden');
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'add_new_comment';
    }
}
