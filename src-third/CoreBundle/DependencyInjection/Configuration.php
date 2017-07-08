<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('xgc_core');

        $rootNode
            ->children()
                ->arrayNode('exceptions')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')->end()
                            ->scalarNode('handler')->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('default_exception_handler')
                    ->defaultValue('Xgc\CoreBundle\Exception\DefaultExceptionHandler')
                ->end()
                ->arrayNode('versions')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->arrayNode('security')
                    ->children()
                        ->arrayNode('password')
                            ->children()
                                ->booleanNode('symbols')->defaultFalse()->end()
                                ->booleanNode('numbers')->defaultFalse()->end()
                                ->booleanNode('uppercases')->defaultFalse()->end()
                                ->integerNode('min_length')->min(4)->defaultValue(8)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
