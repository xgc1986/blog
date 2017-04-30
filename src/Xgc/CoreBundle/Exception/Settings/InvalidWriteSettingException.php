<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Exception\Settings;

use Xgc\CoreBundle\Exception\Http\InternalErrorException;

class InvalidWriteSettingException extends InternalErrorException
{
    public function __construct(string $key, string $type, \Throwable $exception = null)
    {
        parent::__construct("You must use $type to write setting '$key''" , $exception);
    }
}
