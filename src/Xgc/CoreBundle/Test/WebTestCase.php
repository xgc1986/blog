<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Test;

use Symfony\Bundle\FrameworkBundle\Client as SymfonyClient;

class WebTestCase extends KernelTestCase
{
    protected static function createClient(array $options = [], array $server = []): SymfonyClient
    {
        self::loadKernel($options);

        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }
}
