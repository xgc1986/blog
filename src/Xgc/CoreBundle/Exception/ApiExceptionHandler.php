<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Xgc\CoreBundle\Exception\Api\ApiException;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;

class ApiExceptionHandler extends ExceptionHandler
{

    public function getResponse(): ?Response
    {
        if (!$this->exception) {
            return null;
        }

        return new JsonResponse($this->exception->getExtras(), $this->exception->getStatus());
    }


    /**
     * {@inheritDoc}
     *
     * public function throwInternalServerError(Exception $exception, ?string $message = null): void
     * {
     * if ($this->container->getParameter('kernel.environment') === "prod") {
     * throw new ApiException(500, "Internal server error.");
     * } else {
     * $trace = $exception->getTrace();
     * $trace[0]["line"] = $exception->getLine();
     * throw new ApiException(500, $message ?? $exception->getMessage(), ['trace' => $trace]);
     * }
     *
     * }*/
}
