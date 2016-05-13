<?php

namespace Etfostra\PageLayoutBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Etfostra\PageLayoutBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('etfostra_page_layout');

        $rootNode
            ->children()
                ->arrayNode('grid_settings')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('always_show_resize_handle')->defaultValue(false)->end()
                        ->booleanNode('animate')->defaultValue(true)->end()
                        ->booleanNode('auto')->defaultValue(true)->end()
                        ->integerNode('cell_height')->defaultValue(80)->end()
                        ->arrayNode('draggable')
                            ->children()
                                ->scalarNode('handle')->defaultValue('.grid-stack-item-content')->end()
                                ->booleanNode('scroll')->defaultValue(true)->end()
                                ->scalarNode('appendTo')->defaultValue('body')->end()
                            ->end()
                        ->end()
                        ->scalarNode('handle')->defaultValue('.grid-stack-item-content')->end()
                        ->scalarNode('handle_class')->defaultValue('')->end()
                        ->integerNode('height')->defaultValue(0)->end()
                        ->booleanNode('float')->defaultValue(false)->end()
                        ->scalarNode('item_class')->defaultValue('grid-stack-item')->end()
                        ->integerNode('min_width')->defaultValue(768)->end()
                        ->scalarNode('placeholder_class')->defaultValue('grid-stack-placeholder')->end()
                        ->scalarNode('placeholder_text')->defaultValue('')->end()
                        ->arrayNode('resizable')
                            ->children()
                                ->booleanNode('autoHide')->defaultValue(true)->end()
                                ->scalarNode('handles')->defaultValue('se')->end()
                            ->end()
                        ->end()
                        ->booleanNode('static_grid')->defaultValue(false)->end()
                        ->integerNode('vertical_margin')->defaultValue(10)->end()
                        ->integerNode('width')->defaultValue(12)->end()
                        ->integerNode('item_min_width')->cannotBeEmpty()->defaultValue(4)->end()
                        ->arrayNode('widgets_container')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('properties')
                    ->prototype('array')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('front_layout')
                            ->cannotBeEmpty()
                            ->defaultValue('EtfostraPageLayoutBundle:Frontend:page_layout.html.twig')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
