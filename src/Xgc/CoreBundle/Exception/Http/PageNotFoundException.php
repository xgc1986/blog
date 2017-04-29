<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class PageNotFoundException extends HttpException
{

    function __construct(?string $message = null, \Throwable $exception = null)
    {
        parent::__construct(400, $message ?? "Page not found", [], $exception);
    }
}
