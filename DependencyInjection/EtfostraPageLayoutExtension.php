<?php

namespace Etfostra\PageLayoutBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;


class EtfostraPageLayoutExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

//        $container->setParameter('etfostra_ru_strings.redis_cache_ttl', $config['redis_cache_ttl']);
//        $container->setParameter('etfostra_ru_strings.pyphrasy_api_url', $config['pyphrasy_api_url']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
