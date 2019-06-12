<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * AppKernel.
 */
class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),

            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            new Liip\FunctionalTestBundle\LiipFunctionalTestBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Sensio\Bundle\BuzzBundle\SensioBuzzBundle(),

            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\SeoBundle\SonataSeoBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Sonata\MediaBundle\SonataMediaBundle(),

            new Vich\UploaderBundle\VichUploaderBundle(),

            new Stfalcon\Bundle\BlogBundle\StfalconBlogBundle(),
            new Stfalcon\Bundle\PortfolioBundle\StfalconPortfolioBundle(),
            new Stfalcon\ReCaptchaBundle\StfalconReCaptchaBundle(),

            new Application\Bundle\DefaultBundle\ApplicationDefaultBundle(),

            new FOS\UserBundle\FOSUserBundle(),
            new Application\Bundle\UserBundle\ApplicationUserBundle(),
            new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new Application\Bundle\MediaBundle\ApplicationMediaBundle(),

            new JMS\TranslationBundle\JMSTranslationBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),
            new Stfalcon\RedirectBundle\StfalconRedirectBundle(),
            new Fresh\DoctrineEnumBundle\FreshDoctrineEnumBundle(),
            new Hype\MailchimpBundle\HypeMailchimpBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),

            new IAkumaI\SphinxsearchBundle\SphinxsearchBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),

            new TFox\MpdfPortBundle\TFoxMpdfPortBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
        ];

        if (in_array($this->getEnvironment(), ['prod'], true)) {
            $bundles[] = new Sentry\SentryBundle\SentryBundle();
        }

        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
