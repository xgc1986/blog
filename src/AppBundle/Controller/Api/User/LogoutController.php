<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Helper\DoctrineHelper;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;

class LogoutController extends Controller
{
    public function indexAction(): JsonResponse
    {
        $this->get('xgc.security')->logout();

        return new JsonResponse();
    }
}
