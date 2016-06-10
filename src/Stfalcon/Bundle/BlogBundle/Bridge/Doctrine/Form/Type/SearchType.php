<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * SearchType
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class SearchType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('searchPhrase', 'text', [
                'attr' => [
                    'class' => 'search-phrase',
                ],
                'label' => 'Search',
            ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'search';
    }
}
