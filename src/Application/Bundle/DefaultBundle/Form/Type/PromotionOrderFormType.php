<?php

namespace Application\Bundle\DefaultBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PromotionOrderFormType
 *
 * @package Application\DefaultBundle\Form\Type
 */
class PromotionOrderFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                [
                    'label' => 'Your name',
                    'constraints' => [new Assert\Length(array('max' => 64))]
                ]
            )
            ->add(
                'email',
                'email',
                [
                    'label' => 'Your email',
                ]
            )
            ->add(
                'message',
                'textarea',
                [
                    'label' => 'Your message',
                    'constraints' => [new Assert\Length(array('max' => 5000))]
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'order_promotion';
    }
} 