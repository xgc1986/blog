<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class InternalErrorException extends HttpException
{

    function __construct(?string $message = null, \Throwable $exception = null)
    {
        parent::__construct(500, $message ?? "Internal server error", [], $exception);
    }

    function getBody()
    {
        return $this->extras;
    }
}
