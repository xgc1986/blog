<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class XgcInfluxExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('xgc.influx.user', $config['user']);
        $container->setParameter('xgc.influx.host', $config['host']);
        $container->setParameter('xgc.influx.pass', $config['pass']);
        $container->setParameter('xgc.influx.database', $config['database']);
        $container->setParameter('xgc.influx.port', $config['port']);

        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'xgc_influx';
    }
}
