<?php

namespace Mablae\StoreLocator\Bundle\DependencyInjection;

use InvalidArgumentException;
use Mablae\StoreLocator\StoreLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class MablaeStoreLocatorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $bundles = $container->getParameter('bundles');
        if (!isset($bundles['BazingaGeocoderBundle'])) {
            throw new InvalidArgumentException(
                'The BazingaGeocoderBundle needs to be registered in order to use StoreLocatorBundle.'
            );
        }

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $storeLocator = new Definition(StoreLocator::class, [
            new Reference($config['repository_service']),
            new Reference('geocoder'),
            new Reference($config['distance_calculator'])
        ]);

        $container->addDefinitions([
            StoreLocator::class => $storeLocator
        ]);
    }
}
