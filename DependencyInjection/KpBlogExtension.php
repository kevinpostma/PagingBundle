<?php

namespace Kp\Bundle\PagingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Kp\Bundle\PagingBundle\KpPagingBundle;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KpPagingExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $driver = $config['driver'];

        if (!in_array($driver, KpPagingBundle::getSupportedDrivers())) {
            throw new \InvalidArgumentException(sprintf('Driver "%s" is unsupported for KpPagingBundle', $driver));
        }

        $loader->load(sprintf('driver/%s.xml', $driver));

        $container->setParameter('kp_paging.driver', $driver);
        $container->setParameter('kp_paging.engine', $config['engine']);

        $this->mapClassParameters($config['classes'], $container);

        $loader->load('services.xml');
    }

    protected function mapClassParameters(array $classes, ContainerBuilder $container)
    {
        foreach ($classes as $model => $serviceClasses) {
            foreach ($serviceClasses as $service => $class) {
                $service = $service === 'form' ? 'form.type' : $service;
                $container->setParameter(sprintf('kp.%s.%s.class', $service, $model), $class);
            }
        }
    }
}
