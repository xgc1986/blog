<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class RequestBodyTooLargeException extends HttpException
{

    function __construct(?string $message = null, ?\Throwable $exception = null)
    {
        parent::__construct(413, $message ?? "Request body is too large", [], $exception);
    }
}
