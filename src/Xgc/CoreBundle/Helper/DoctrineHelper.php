<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Helper;

use Xgc\CoreBundle\Entity\Entity;

class DoctrineHelper
{
    private static $instance;
    protected $map;

    public static function getInstance(): DoctrineHelper
    {
        self::$instance = self::$instance ?? new DoctrineHelper;
        return self::$instance;
    }

    private function __construct()
    {
        $this->map = [];
    }
}
