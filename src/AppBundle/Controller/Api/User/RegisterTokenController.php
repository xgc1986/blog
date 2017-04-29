<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;

class RegisterTokenController extends Controller
{
    public function indexAction(): JsonResponse
    {
        $token = $this->request->check('token');
        $user = $this->get('xgc.security')->enable("AppBundle:User", $token);

        return new JsonResponse(
            [
                'user' => $this->toArray($user),
            ]
        );
    }

    public function askAction(): JsonResponse
    {
        $email = $this->request->check('email');
        $this->get('xgc.security')->addRegisterToken("AppBundle:user", $email);

        return new JsonResponse();
    }
}
