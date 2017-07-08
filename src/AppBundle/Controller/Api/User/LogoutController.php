<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\CoreBundle\Service\XgcSecurity;

/**
 * Class LogoutController
 * @package AppBundle\Controller\Api\User
 */
class LogoutController extends Controller
{
    /**
     * @param XgcSecurity $security
     * @return JsonResponse
     */
    public function indexAction(XgcSecurity $security): JsonResponse
    {
        $security->logout();

        return new JsonResponse();
    }
}

