<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as SymfonyKernelTestCase;
use Xgc\CoreBundle\Helper\SymfonyHelper;

class KernelTestCase extends SymfonyKernelTestCase
{
    protected static function loadKernel(array $options = [])
    {
        static::bootKernel($options);
        SymfonyHelper::getInstance()->setKernel(self::$kernel);
    }
}
