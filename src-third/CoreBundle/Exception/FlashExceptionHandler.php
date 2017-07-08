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

        $referer = $this->container->get('request')->headers->get('referer');
        $this->container->get('session')->getFlashBag()->add('error', $this->exception->getMessage());


        if ($referer) {
            return new RedirectResponse($referer);
        }

        return null;

    }

}
