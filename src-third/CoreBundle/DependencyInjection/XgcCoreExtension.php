<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class XgcCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter("xgc.exceptions", $config['exceptions']);
        $container->setParameter("xgc.default_exception_handler", $config['default_exception_handler']);
        $container->setParameter("xgc.versions", $config['versions']);

        $container->setParameter("xgc.security.password.symbols", $config['security']['password']['symbols']);
        $container->setParameter("xgc.security.password.numbers", $config['security']['password']['numbers']);
        $container->setParameter("xgc.security.password.uppercases", $config['security']['password']['uppercases']);
        $container->setParameter("xgc.security.password.minLength", $config['security']['password']['min_length']);

        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'xgc_core';
    }
}
