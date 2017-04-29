<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class MethodNotAllowedException extends HttpException
{

    function __construct(string $method, ?string $message = null, \Throwable $exception = null)
    {
        parent::__construct(405, $message ?? "Method '%method%' is not allowed", ['method' => $method], $exception);
    }
}
