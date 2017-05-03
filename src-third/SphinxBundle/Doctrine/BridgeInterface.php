<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle\Doctrine;

/**
 * Doctrine bridge interface
 */
interface BridgeInterface
{
    public function parseResults(array $results, string $index): array;
}
