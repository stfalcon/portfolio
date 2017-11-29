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
            ->add(
                'phone',
                'text',
                [
                    'attr'        => [
                        'placeholder' => 'Номер телефона',
                    ],
                    'label'       => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 10, 'max' => 17]),
                        new Assert\Regex(
                            [
                                'pattern' => "/\d+$/",
                                'match' => true,
                            ]
                        ),
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
            ]);

        if (isset($options['data']['environment']) && $options['data']['environment'] === 'prod') {
            $builder->add('captcha', 'recaptcha');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'vacancy_form';
    }
}
