<?php

namespace Etfostra\PageLayoutBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class EtfostraPageLayoutExtension
 * @package Etfostra\PageLayoutBundle\DependencyInjection
 */
class EtfostraPageLayoutExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('etfostra_page_layout.properties', $config['properties']);
        $container->setParameter('etfostra_page_layout.grid_settings', $config['grid_settings']);
        $container->setParameter('etfostra_page_layout.templates', $config['templates']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->registerResources($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function registerResources(ContainerBuilder $container)
    {
        $templatingEngines = $container->getParameter('templating.engines');
        if (in_array('twig', $templatingEngines)) {
            $container->setParameter('twig.form.resources', array_merge(
                array('EtfostraPageLayoutBundle:Form:fields.html.twig'),
                $container->getParameter('twig.form.resources')
            ));
        }
    }
}
