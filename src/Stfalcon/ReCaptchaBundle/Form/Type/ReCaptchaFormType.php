<?php
namespace Stfalcon\ReCaptchaBundle\Form\Type;

use Buzz\Browser;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
     * @var Browser
     */
    private $buzz;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param Request    $request
     * @param Browser    $buzz
     * @param Translator $translator
     * @param string     $siteKey
     * @param string     $secret
     */
    function __construct(Request $request, Browser $buzz, Translator $translator, $siteKey, $secret)
    {
        $this->buzz = $buzz;
        $this->siteKey = $siteKey;
        $this->secret = $secret;
        $this->request = $request;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onSubmitEvent']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['site_key'] = $options['site_key'];
        $view->vars['locale'] = $options['locale'];
        $view->vars['theme'] = $options['theme'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'site_key',
        ]);

        $resolver->setDefaults([
            'site_key' => $this->siteKey,
            'mapped'   => false,
            'locale'   => $this->request->getLocale(),
            'theme'    => 'light',
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

    public function onSubmitEvent(FormEvent $event)
    {
        $reCaptchaResponse = $this->request->request->get('g-recaptcha-response');

        if (empty($reCaptchaResponse)) {
            $event->getForm()->addError(new FormError($this->translator->trans('captcha.invalid', [], 'StfalconReCaptchaBundle')));

            return;
        }

        $response = $this->buzz->submit(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => $this->secret,
                'response' => $reCaptchaResponse,
                'remoteip' => $this->request->getClientIp()
            ]
        );
        $reCaptchaValidationResponse = json_decode($response->getContent());

        if (true !== $reCaptchaValidationResponse->success) {
            $event->getForm()->addError(new FormError($this->translator->trans('captcha.invalid', [], 'StfalconReCaptchaBundle')));
        }
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
