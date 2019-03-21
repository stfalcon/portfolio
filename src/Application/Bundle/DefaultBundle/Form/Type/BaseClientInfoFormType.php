<?php

namespace Application\Bundle\DefaultBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BaseClientInfoFormType.
 */
class BaseClientInfoFormType extends AbstractType
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
                    'constraints' => [
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
                    'constraints' => [
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
                    'constraints' => [
                            new Assert\NotBlank(),
                            new Assert\Regex([
                                'pattern' => '/[0-9\-\(\)\s]+$/',
                            ]),
                        ],
                ]
            )
            ->add(
                'company',
                'text',
                [
                    'label' => 'Your company',
                    'constraints' => [
                            new Assert\Length(['min' => 2, 'max' => 72]),
                        ],
                ]
            )
            ->add(
                'position',
                'text',
                [
                    'label' => 'Your position',
                    'constraints' => [
                            new Assert\Length(['min' => 3, 'max' => 72]),
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
        return 'client_info';
    }
}
