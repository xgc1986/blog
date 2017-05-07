<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('xgc_influx');

        $rootNode
            ->children()
                ->scalarNode('host')->defaultValue('localhostsdfg')->end()
                ->scalarNode('user')->defaultValue('influx')->end()
                ->scalarNode('pass')->defaultValue('influx')->end()
                ->integerNode('port')->defaultValue('8086')->end()
                ->scalarNode('database')->defaultValue('symfony')->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
