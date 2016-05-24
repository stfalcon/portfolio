<?php

namespace Application\Bundle\DefaultBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
                TextType::class,
                [
                    'label'       => 'Your name',
                    'constraints' => [new Assert\Length(['max' => 64])],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Your email',
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'label'       => 'Your message',
                    'constraints' => [new Assert\Length(['max' => 5000])],
                ]
            )
            ->add('captcha', 'recaptcha', [
                'label' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'order_promotion';
    }
}
