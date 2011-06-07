<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                        => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
//    'Application'                    => __DIR__.'/../src',

    'Liip'                           => __DIR__.'/../vendor/bundles',
    'Knplabs'                        => __DIR__.'/../vendor/bundles',
    'Sensio'                         => __DIR__.'/../vendor/bundles',
    'Stfalcon'                       => array(__DIR__.'/../vendor/bundles', __DIR__.'/../src'),

    // @todo: refact
    'Bundle'                         => __DIR__.'/../vendor/bundles',

    'Doctrine\\Common\\DataFixtures' => __DIR__.'/../vendor/doctrine-data-fixtures/lib',
    'Doctrine\\Common'               => __DIR__.'/../vendor/doctrine-common/lib',
    'Doctrine\\DBAL'                 => __DIR__.'/../vendor/doctrine-dbal/lib',
    'Doctrine\\DBAL\\Migrations'     => __DIR__.'/../vendor/doctrine-migrations/lib',
    'Doctrine'                       => __DIR__.'/../vendor/doctrine/lib',
    
    'Zend'                           => __DIR__.'/../vendor/zf2/library',
    'Imagine'                        => __DIR__.'/../vendor/imagine/lib',
    'Monolog'                        => __DIR__.'/../vendor/monolog/src',
    'Metadata'                       => __DIR__.'/../vendor/metadata/src',
    'Gedmo'                          => __DIR__.'/../vendor/doctrine-extensions/lib',
    'Stof'                           => __DIR__.'/../vendor/bundles',
    'Assetic'                        => __DIR__.'/../vendor/assetic/src',
));
$loader->registerPrefixes(array(
    'Twig_Extensions_'               => __DIR__.'/../vendor/twig-extensions/lib',
    'Twig_'                          => __DIR__.'/../vendor/twig/lib',
    'Zend_'                          => __DIR__.'/../vendor/zf/library',
));
//$loader->registerPrefixFallbacks(array(
//    __DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs',
//));
$loader->registerNamespaceFallbacks(array(
    __DIR__.'/../src',
));
$loader->register();

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__.'/../vendor/zf/library'),
//    realpath(__DIR__.'/../vendor/zf2/library'),
    get_include_path(),
)));

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once __DIR__.'/../vendor/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload(__DIR__.'/../vendor/swiftmailer/lib/swift_init.php');