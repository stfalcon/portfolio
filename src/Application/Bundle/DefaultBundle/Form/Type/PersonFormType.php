<?php

namespace Application\Bundle\DefaultBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PersonFormType
 */
class PersonFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projectName = $options['project_name'];
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
                'projectName',
                'hidden',
                [
                    'data' => $projectName,
                    'constraints' =>
                        [
                            new Assert\NotBlank(),
                            new Assert\Length(['min' => 2, 'max' => 128]),
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
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'project_name' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'person_form';
    }
}
