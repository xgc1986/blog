<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use AppBundle\Entity\User;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Exception\Http\AccountAlreadyExistsException;
use Xgc\CoreBundle\Exception\Http\InvalidParamException;
use Xgc\CoreBundle\Exception\Http\PreconditionFailedException;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\CoreBundle\Service\Doctrine;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\UserService;
use Xgc\UtilsBundle\Helper\Text;

/**
 * Class RegisterController
 * @package AppBundle\Controller\Api\User
 */
class RegisterController extends Controller
{
    /**
     * @param Request $request
     * @param UserService $userService
     * @param Doctrine $doctrine
     * @return JsonResponse
     */
    public function indexAction(Request $request, UserService $userService, Doctrine $doctrine): JsonResponse
    {
        $username  = $request->fetch('username');
        $password  = $request->fetch('password');
        $password2 = $request->fetch('password2');
        $email     = $request->fetch('email');

        if ($password !== $password2) {
            throw new PreconditionFailedException("Passwords missmatch");
        }

        if (!Text::validatePassword($password, 8, true, true, true, false)) {
            throw new InvalidParamException('password', "Password insecure");
        }

        if ($userService->find($username)) {
            throw new AccountAlreadyExistsException();
        }

        if ($userService->find($email)) {
            throw new AccountAlreadyExistsException();
        }

        $user = new User();
        $user->setUsername($username);
        $userService->setPassword($user, $password);
        $user->setEmail($email);
        $user->setEnabled(true);
        $user->setClientIp($request->getIP());

        $doctrine->flush($user);

        return new JsonResponse(['user' => $user]);
    }
}
