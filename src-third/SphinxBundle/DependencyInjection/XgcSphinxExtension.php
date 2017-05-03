<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class XgcSphinxExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter("xgc.sphinx.bin", $config['bin']);
        $container->setParameter("xgc.sphinx.conf", $config['conf']);

        $container->setParameter('xgc.sphinx.searchd.host', $config['searchd']['host']);
        $container->setParameter('xgc.sphinx.searchd.port', $config['searchd']['port']);
        $container->setParameter('xgc.sphinx.searchd.socket', $config['searchd']['socket']);

        $container->setParameter('xgc.sphinx.indexes', $config['indexes']);

        $container->setParameter('xgc.sphinx.api_file', __DIR__ . '/../Sphinx/SphinxAPI.php');

        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'xgc_sphinx';
    }
}
