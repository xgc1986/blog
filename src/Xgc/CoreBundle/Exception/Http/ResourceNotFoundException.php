<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class ResourceNotFoundException extends HttpException
{

    function __construct(string $resource, ?string $message = null, \Throwable $exception = null)
    {
        parent::__construct(400, $message ?? "%resource% not found", ['resource' => $resource], $exception);
    }
}
