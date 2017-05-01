<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class ResourceAlreadyExistsException extends HttpException
{

    function __construct(string $resource, $value, ?string $message = null, \Throwable $exception = null)
    {
        parent::__construct(
            409,
            $message ?? "%resource% '%value%' already exists",
            [
                'resource' => $resource,
                'value'    => $value,
            ],
            $exception
        );
    }
}
