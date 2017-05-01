<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Exception\Settings;

use Xgc\CoreBundle\Exception\Http\InternalErrorException;

class SettingNotFoundException extends InternalErrorException
{
    public function __construct(string $key, \Throwable $exception = null)
    {
        parent::__construct("Setting '$key' does not exist'" , $exception);
    }
}
