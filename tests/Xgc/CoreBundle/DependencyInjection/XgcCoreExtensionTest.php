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
}
