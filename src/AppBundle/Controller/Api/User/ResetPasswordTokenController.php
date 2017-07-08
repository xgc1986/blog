<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use AppBundle\Entity\User;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\XgcSecurity;

/**
 * Class ResetPasswordTokenController
 * @package AppBundle\Controller\Api\User
 */
class ResetPasswordTokenController extends Controller
{
    public function indexAction(Request $request, XgcSecurity $security): JsonResponse
    {
        $token     = $request->fetch('token');
        $password  = $request->fetch('password');
        $password2 = $request->fetch('password2');

        $user = $security->resetPassword(User::class, $token, $password, $password2);

        return new JsonResponse([
            'user' => $user,
        ]);
    }

    public function askAction(Request $request, XgcSecurity $security): JsonResponse
    {
        $email = $request->fetch('email');
        $security->addResetPasswordToken(User::class, $email);

        return new JsonResponse();
    }
}
