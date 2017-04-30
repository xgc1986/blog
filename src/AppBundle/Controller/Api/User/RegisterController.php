<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use AppBundle\Entity\User;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Exception\Http\InvalidParamException;
use Xgc\CoreBundle\Exception\Http\PreconditionFailedException;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\UtilsBundle\Helper\JsonHelper;
use Xgc\UtilsBundle\Helper\Text;

class RegisterController extends Controller
{
    public function indexAction(): JsonResponse
    {
        $username = $this->request->check('username');
        $password = $this->request->check('password');
        $password2 = $this->request->check('password2');
        $email = $this->request->check('email');

        if ($password !== $password2) {
            throw new PreconditionFailedException("Passwords missmatch");
        }

        if (!Text::validatePassword($password, 8, true, true, true, false)) {
            throw new InvalidParamException('password', "Password insecure");
        }

        $user = new User();
        $user->setUsername($username);
        $this->get('xgc.entity.user')->setPassword($user, $password);
        $user->setEmail($email);
        $user->setEnabled(true);
        $user->setClientIp($this->request->getIp());

        // validate
        $this->get('xgc.validator')->validate($user, $this->http);

        $manager = $this->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        $result = [];
        JsonHelper::getInstance()->encode($user, $result, 'user');
        return new JsonResponse($result);
    }
}
