<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Xgc\CoreBundle\DependencyInjection\Configuration;

/**
 * @codeCoverageIgnore
 */
class ConfigurationTest extends TestCase
{
    public function testConfigTreeBuilder()
    {
        $conf = new Configuration();
        $tree = $conf->getConfigTreeBuilder();

        self::assertNotNull($conf);
        self::assertTrue($tree instanceof TreeBuilder);
    }
}
