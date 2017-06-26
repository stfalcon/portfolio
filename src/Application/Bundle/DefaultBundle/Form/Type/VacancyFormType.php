<?php

namespace Application\Bundle\DefaultBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VacancyFormType
 */
class VacancyFormType extends AbstractType
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
                    'attr'        => [
                        'placeholder' => 'Ваше имя',
                    ],
                    'label'       => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => 64]),
                    ],
                ]
            )
            ->add(
                'email',
                'email',
                [
                    'attr'        => [
                        'placeholder' => 'Электронная почта',
                    ],
                    'label'       => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => 64]),
                    ],
                ]
            )
            ->add('attach', 'file', [
                'label'       => false,
                'required'    => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '20M',
                    ]),
                ],
            ])
            ->add('captcha', 'recaptcha', [
                'label' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'vacancy_form';
    }
}
