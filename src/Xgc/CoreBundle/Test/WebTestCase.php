<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Test;

use Symfony\Bundle\FrameworkBundle\Client as SymfonyClient;
use Xgc\CoreBundle\Helper\SymfonyHelper;

class WebTestCase extends KernelTestCase
{
    protected static function createClient(array $options = [], array $server = []): SymfonyClient
    {
        self::loadKernel($options);

        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);
        SymfonyHelper::getInstance()->getKernel(static::$kernel);

        return $client;
    }
}
