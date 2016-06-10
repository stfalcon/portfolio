<?php
namespace Stfalcon\ReCaptchaBundle\Form\Type;

use Stfalcon\ReCaptchaBundle\Validator\Constraints\ValidCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReCaptchaFormType extends AbstractType
{
    /**
     * @var string
     */
    private $siteKey;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request    $request
     * @param string     $siteKey
     * @param string     $secret
     */
    public function __construct(Request $request, $siteKey, $secret)
    {
        $this->siteKey = $siteKey;
        $this->secret = $secret;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['site_key'] = $options['site_key'];
        $view->vars['locale'] = $options['locale'];
        $view->vars['theme'] = $options['theme'];
        $view->vars['callback_name'] = $options['callback_name'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'site_key',
        ]);

        $resolver->setDefaults([
            'compound'      => false,
            'site_key'      => $this->siteKey,
            'mapped'        => false,
            'locale'        => $this->request->getLocale(),
            'theme'         => 'light',
            'callback_name' => 'showSubmitButton',
            'constraints'   => [
                new ValidCaptcha(),
            ],
        ]);

        $resolver->setAllowedValues([
            'locale' => [
                'ar',
                'bg',
                'ca',
                'zh-CN',
                'zh-TW',
                'hr',
                'cs',
                'da',
                'nl',
                'en-GB',
                'en',
                'fil',
                'fi',
                'fr',
                'fr-CA',
                'de',
                'de-AT',
                'de-CH',
                'el',
                'iw',
                'hi',
                'hu',
                'id',
                'it',
                'ja',
                'ko',
                'lv',
                'lt',
                'no',
                'fa',
                'pl',
                'pt',
                'pt-BR',
                'pt-PT',
                'ro',
                'ru',
                'sr',
                'sk',
                'sl',
                'es',
                'es-419',
                'sv',
                'th',
                'tr',
                'uk',
                'vi',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'recaptcha';
    }
}
