<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class ResourceNoLongerAvailableException extends HttpException
{

    function __construct(string $resource, ?string $message = null, \Throwable $exception = null)
    {
        parent::__construct(410, $message ?? "The resource '%resource%' is no longer available", ['resource' => $resource], $exception);
    }
}
