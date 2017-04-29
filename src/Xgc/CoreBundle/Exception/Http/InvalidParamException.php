<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class InvalidParamException extends HttpException
{

    function __construct(string $name, ?string $message = null, \Throwable $exception = null)
    {
        parent::__construct(400, $message ?? "Invalid param %name%", ['param' => $name], $exception);
    }
}
