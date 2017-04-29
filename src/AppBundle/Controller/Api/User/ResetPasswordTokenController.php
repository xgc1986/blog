<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;

class ResetPasswordTokenController extends Controller
{
    public function indexAction(): JsonResponse
    {
        $token = $this->request->check('token');
        $password = $this->request->check('password');
        $password2 = $this->request->check('password2');

        $user = $this->get('xgc.security')->resetPassword("AppBundle:User", $token, $password, $password2);

        return new JsonResponse(
            [
                'user' => $this->toArray($user),
            ]
        );
    }

    public function askAction(): JsonResponse
    {
        $email = $this->request->check('email');
        $this->get('xgc.security')->addResetPasswordToken("AppBundle:user", $email);

        return new JsonResponse();
    }
}
