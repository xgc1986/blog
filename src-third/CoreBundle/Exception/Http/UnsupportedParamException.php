<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class UnsupportedParamException extends HttpException
{

    function __construct(string $name, array $allowed, ?string $message = null, \Throwable $exception = null)
    {
        parent::__construct(404, $message ?? "Unsupported param '%param%'", ['param' => $name, 'allowed' => $allowed], $exception);
    }
}
