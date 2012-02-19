<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                        => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
//    'Application'                    => __DIR__.'/../src',

    'Liip'                           => __DIR__.'/../vendor/bundles',
    'Knp\Bundle'                     => __DIR__.'/../vendor/bundles',
    'Knp\Menu'                       => __DIR__.'/../vendor/KnpMenu/src',

    'Sensio'                         => __DIR__.'/../vendor/bundles',
    'Stfalcon'                       => array(__DIR__.'/../vendor/bundles', __DIR__.'/../src'),

    // @todo: refact
    'Bundle'                         => __DIR__.'/../vendor/bundles',

    'Doctrine\\Bundle'               => __DIR__.'/../vendor/bundles',
    'Doctrine\\Common\\DataFixtures' => __DIR__.'/../vendor/doctrine-fixtures/lib',
    'Doctrine\\Common'               => __DIR__.'/../vendor/doctrine-common/lib',
    'Doctrine\\DBAL'                 => __DIR__.'/../vendor/doctrine-dbal/lib',
    'Doctrine\\DBAL\\Migrations'     => __DIR__.'/../vendor/doctrine-migrations/lib',
    'Doctrine'                       => __DIR__.'/../vendor/doctrine/lib',

    'Zend'                           => __DIR__.'/../vendor/zf2/library',
    'Imagine'                        => __DIR__.'/../vendor/imagine/lib',
    'Monolog'                        => __DIR__.'/../vendor/monolog/src',
    'Metadata'                       => __DIR__.'/../vendor/metadata/src',
    'Gedmo'                          => __DIR__.'/../vendor/gedmo-doctrine-extensions/lib',
    'Stof'                           => __DIR__.'/../vendor/bundles',
    'Assetic'                        => __DIR__.'/../vendor/assetic/src',
));
$loader->registerPrefixes(array(
    'Twig_Extensions_'               => __DIR__.'/../vendor/twig-extensions/lib',
    'Twig_'                          => __DIR__.'/../vendor/twig/lib',
    'Zend_'                          => __DIR__.'/../vendor/zf/library',
));

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->registerPrefixFallbacks(array(__DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs'));
}

$loader->registerNamespaceFallbacks(array(
    __DIR__.'/../src',
));
$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile(__DIR__.'/../vendor/doctrine/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__.'/../vendor/zf/library'),
    get_include_path(),
)));

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once __DIR__.'/../vendor/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload(__DIR__.'/../vendor/swiftmailer/lib/swift_init.php');