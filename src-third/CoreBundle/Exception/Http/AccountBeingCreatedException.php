<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class AccountBeingCreatedException extends HttpException
{

    function __construct(?string $message = null, ?\Throwable $exception = null)
    {
        parent::__construct(409, $message ?? "The account is not activated", [], $exception);
    }
}
