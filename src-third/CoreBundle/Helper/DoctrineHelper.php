<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Helper;

class DoctrineHelper
{
    private static $instance;

    public static function getInstance(): DoctrineHelper
    {
        self::$instance = self::$instance ?? new DoctrineHelper();

        return self::$instance;
    }

    private function __construct()
    {
    }
}
