<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Exception;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Xgc\CoreBundle\Exception\Api\ApiException;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;

class FlashExceptionHandler extends ExceptionHandler
{

    public function getResponse(): ?Response
    {
        if (!$this->exception) {
            return null;
        }

        $referer = $this->container->get('xgc.request')->headers->get('referer');
        $this->container->get('session')->getFlashBag()->add('error', $this->exception->getMessage() . " $referer");


        if ($referer) {
            return new RedirectResponse($referer);
        }

        return null;

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
