<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use AppBundle\Entity\User;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\XgcSecurity;

/**
 * Class LoginController
 * @package AppBundle\Controller\Api\User
 */
class LoginController extends Controller
{
    /**
     * @param Request $request
     * @param XgcSecurity $security
     * @return JsonResponse
     */
    public function indexAction(Request $request, XgcSecurity $security): JsonResponse
    {
        $username = $request->fetch('username');
        $password = $request->fetch('password');

        $user = $security->login(User::class, "main", $username, $password, true);

        return new JsonResponse(['user' => $user]);
    }
}
