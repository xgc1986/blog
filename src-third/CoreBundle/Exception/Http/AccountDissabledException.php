<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception\Http;

use Xgc\CoreBundle\Exception\HttpException;

class AccountDissabledException extends HttpException
{

    function __construct(?string $message = null, ?\Throwable $exception = null)
    {
        parent::__construct(403, $message ?? "Account is dissabled", [], $exception);
    }
}
