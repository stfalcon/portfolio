<?php

namespace Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Stfalcon\Bundle\BlogBundle\Bridge\Doctrine\Form\DataTransformer\EntitiesToStringTransformer;

/**
 * Form type for tags
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class TagsType extends AbstractType
{

    protected $registry;

    /**
     * Constructor injection
     *
     * @param RegistryInterface $registry Doctrine registry object
     *
     * @return void
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

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
        $builder->prependClientTransformer(
            new EntitiesToStringTransformer($this->registry->getEntityManager())
        );
    }

    /**
     * Returns the name of the parent type.
     *
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tags';
    }
}