<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Exception;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Xgc\CoreBundle\Exception\Api\ApiException;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;

class ApiExceptionHandler extends ExceptionHandler
{

    public function getResponse(): ?Response
    {
        return new JsonResponse($this->exception->getExtras(), $this->exception->getStatus());
    }



    /**
     * {@inheritDoc}
     *
    public function throwInternalServerError(Exception $exception, ?string $message = null): void
    {
        if ($this->container->getParameter('kernel.environment') === "prod") {
            throw new ApiException(500, "Internal server error.");
        } else {
            $trace = $exception->getTrace();
            $trace[0]["line"] = $exception->getLine();
            throw new ApiException(500, $message ?? $exception->getMessage(), ['trace' => $trace]);
        }

    }*/
}
