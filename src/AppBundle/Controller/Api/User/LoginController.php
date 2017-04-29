<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Helper\DoctrineHelper;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\UtilsBundle\Helper\JsonHelper;

class LoginController extends Controller
{
    public function indexAction(): JsonResponse
    {
        $username = $this->request->check('username');
        $password = $this->request->check('password');

        $user = $this->get('xgc.security')->login("AppBundle:User", "api", $username, $password, true);

        $resp = [];
        JsonHelper::getInstance()->encode($user, $resp, 'user');
        return new JsonResponse($resp);
    }
}
