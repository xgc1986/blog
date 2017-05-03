<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @codeCoverageIgnore
 */
class XgcCoreExtensionTest extends TestCase
{

    public function testConstruct()
    {
        $extension = new XgcCoreExtension;
        self::assertNotNull($extension);

        return $extension;
    }

    /**
     * @depends testConstruct
     * @param Extension $extension
     */
    public function testGetAlias(Extension $extension)
    {
        self::assertEquals('xgc_core', $extension->getAlias());
    }

    /**
     * @depends testConstruct
     * @param Extension $extension
     */
    public function testLoad(Extension $extension)
    {
        $config = [
            'xgc' => [
                'exceptions' => [
                    [
                        'host'    => 'api.localhost',
                        'handler' => 'Xgc\CoreBundle\Exception\ApiExceptionHandler',
                    ],
                ],
            ],
        ];

        $containerBuilder = new ContainerBuilder;
        $extension->load($config, $containerBuilder);
        $param = $containerBuilder->getParameter('xgc.exceptions');

        self::assertEquals($config['xgc']['exceptions'], $param);
    }
}
