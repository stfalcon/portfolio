<?php
namespace Application\Bundle\DefaultBundle\Form\Type;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class DirectOrderFormType
 */
class DirectOrderFormType extends AbstractType
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
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
                    'attr' => [
                        'placeholder' => $this->translator->trans('Ваше имя')
                    ],
                    'label' => false,
                    'constraints' => [new Assert\Length(array('max' => 64))]
                ]
            )
            ->add(
                'email',
                'email',
                [
                    'attr' => [
                        'placeholder' => $this->translator->trans('Электронная почта')
                    ],
                    'label' => false,
                ]
            )
            ->add(
                'phone',
                'text',
                [
                    'attr' => [
                        'placeholder' => $this->translator->trans('Телефон')
                    ],
                    'label' => false,
                ]
            )
            ->add(
                'message',
                'textarea',
                [
                    'attr' => [
                        'placeholder' => $this->translator->trans('Добрый день! Меня интересует создание мобильного приложения под iOS. Мой бюджет 10 000 $. Хочу работать с Stfalcon.')
                    ],
                    'label' => false,
                    'constraints' => [new Assert\Length(array('max' => 5000))]
                ]
            )
            ->add('attach', 'file', [
                'label'         => false,
                'required'      => false,
                'constraints'   => [
                    new Assert\File([
                        'maxSize' => '20M',
                    ])
                ]
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