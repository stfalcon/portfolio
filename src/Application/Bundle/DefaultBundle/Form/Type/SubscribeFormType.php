<?php
namespace Application\Bundle\DefaultBundle\Form\Type;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SubscribeFormType
 */
class SubscribeFormType extends AbstractType
{
    /**
     * @var Translator $translator Translator
     */
    private $translator;

    /**
     * Constructor
     *
     * @param Translator $translator Translator
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
                'email',
                'email',
                [
                    'label' => false,
                    'constraints' => [
                        new Assert\NotBlank(['message' => $this->translator->trans('Вы не указали e-mail адрес')]),
                        new Assert\Email(['message' => $this->translator->trans('Недопустимый адрес электронной почты')]),
                        new Assert\Length(array('max' => 64))
                    ]
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
