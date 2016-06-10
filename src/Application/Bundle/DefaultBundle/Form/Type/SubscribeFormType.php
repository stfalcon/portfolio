<?php
namespace Application\Bundle\DefaultBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SubscribeFormType
 */
class SubscribeFormType extends AbstractType
{
    /**
     * @var TranslatorInterface $translator TranslatorInterface
     */
    private $translator;

    /**
     * Constructor
     *
     * @param TranslatorInterface $translator TranslatorInterface
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
                'email',
                'email',
                [
                    'label'       => false,
                    'constraints' => [
                        new Assert\NotBlank(['message' => $this->translator->trans('Вы не указали e-mail адрес')]),
                        new Assert\Email(['message' => $this->translator->trans('Недопустимый адрес электронной почты')]),
                        new Assert\Length(['max' => 64]),
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'subscribe';
    }
}
