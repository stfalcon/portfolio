<?php
namespace Application\Bundle\DefaultBundle\Form\Type;

use Stfalcon\ReCaptchaBundle\Form\Type\ReCaptchaFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DirectOrderFormType
 */
class DirectOrderFormType extends AbstractType
{
    /**
     * @var TranslatorInterface $translator Translator interface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator Translator interface
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
                        'placeholder' => $this->translator->trans('Ваше имя'),
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
                        'placeholder' => $this->translator->trans('Электронная почта'),
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
                        'placeholder' => $this->translator->trans('Телефон'),
                    ],
                    'label'       => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => 64]),
                        new Assert\Regex([
                            'pattern' => '/^\+?[0-9\(\)\/ \-]+$/',
                            'message' => 'Неверный формат телефона',
                        ]),
                    ],
                ]
            )
            ->add(
                'message',
                'textarea',
                [
                    'attr'        => [
                        'placeholder' => $this->translator->trans(<<<TEXT
Несколько слов о проекте
TEXT
                        ),
                    ],
                    'label'       => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'min' => 30,
                            'max' => 5000,
                        ]),
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
        return 'direct_order';
    }
}
