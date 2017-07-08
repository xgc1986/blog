<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\XgcSecurity;

/**
 * Class RegisterTokenController
 * @package AppBundle\Controller\Api\User
 */
class RegisterTokenController extends Controller
{

    /**
     * @param Request $request
     * @param XgcSecurity $security
     * @return JsonResponse
     */
    public function indexAction(Request $request, XgcSecurity $security): JsonResponse
    {
        $token = $request->fetch('token');
        $user  = $security->enable("AppBundle:User", $token);

        return new JsonResponse([
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param XgcSecurity $security
     * @return JsonResponse
     */
    public function askAction(Request $request, XgcSecurity $security): JsonResponse
    {
        $email = $request->fetch('email');
        $security->addRegisterToken("AppBundle:user", $email);

        return new JsonResponse();
    }
}
