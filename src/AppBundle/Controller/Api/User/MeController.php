<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\CoreBundle\Service\XgcSecurity;

/**
 * Class MeController
 * @package AppBundle\Controller\Api\User
 */
class MeController extends Controller
{

    /**
     * @param XgcSecurity $security
     * @return JsonResponse
     */
    public function indexAction(XgcSecurity $security): JsonResponse
    {
        $user = $security->checkUser();

        return new JsonResponse([
            'user' => $user
        ]);
    }
}
