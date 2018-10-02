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
                'text',
                [
                    'label'       => 'Your name',
                    'constraints' =>
                        [
                            new Assert\NotBlank(),
                            new Assert\Length(['min' => 3, 'max' => 64]),
                        ],
                ]
            )
            ->add(
                'email',
                'email',
                [
                    'label' => 'Your email',
                    'constraints' =>
                        [
                            new Assert\NotBlank(),
                            new Assert\Length(['min' => 6, 'max' => 72]),
                        ],
                ]
            )
            ->add(
                'phone',
                'text',
                [
                    'label' => 'Your phone',
                    'constraints' =>
                        [
                            new Assert\NotBlank(),
                        ],
                ]
            )
            ->add(
                'company',
                'text',
                [
                    'label' => 'Your company',
                    'constraints' =>
                        [
                            new Assert\Length(['min' => 2, 'max' => 72]),
                        ],
                ]
            )
            ->add(
                'position',
                'text',
                [
                    'label' => 'Your position',
                    'constraints' =>
                        [
                            new Assert\Length(['min' => 3, 'max' => 72]),
                        ],
                ]
            )
            ->add(
                'message',
                'textarea',
                [
                    'label'       => 'Your message',
                    'constraints' =>
                        [
                            new Assert\NotBlank(),
                            new Assert\Length(['min' => 30, 'max' => 5000]),
                        ],
                ]
            )
            ->add(
                'budget',
                'choice',
                [
                    'required'    => false,
                    'empty_value' => false,
                    'label'       => 'Your budget',
                    'expanded'    => true,
                    'choices'     => [
                        '$30 000 – $40 000'   => '$30 000 – $40 000',
                        '$40 000 – $60 000'   => '$40 000 – $60 000',
                        '$60 000 – $100 000'  => '$60 000 – $100 000',
                        '$100 000 – $150 000' => '$100 000 – $150 000',
                    ],
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
