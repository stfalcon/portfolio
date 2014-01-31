<?php

namespace Stfalcon\Bundle\BlogBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages StfalconBlogBundle configuration
 *
 * @author Stepan Tanasiychuk <ceo@stfalcon.com>
 */
class StfalconBlogExtension extends Extension
{

    /**
     * Load configuration from services.xml
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = array();
        foreach ($configs as $c) {
            $config = array_merge($config, $c);
        }

        $container->setParameter('stfalcon_blog.config', $config);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('service.xml');
    }

}