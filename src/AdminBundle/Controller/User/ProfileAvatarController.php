<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\UserService;

/**
 * Class ProfileAvatarController
 * @package AdminBundle\Controller\User
 */
class ProfileAvatarController extends Controller
{

    /**
     * @Route("/profile/avatar/update")
     * @Method({"POST"})
     * @param Request $request
     * @param UserService $userService
     * @return Response
     */
    public function updateAction(Request $request, UserService $userService)
    {
        $file = $request->fetchFile('avatar');

        $user = $this->getUser();
        $id   = $user->getId();

        $userService->uploadAvatar($user, $file, "/images/$id", "avatar");
        $this->addFlash('notice', 'Tu avatar ha sido actualizado correctamente');
        return $this->redirectToRoute('admin_user_profile_index');
    }
}
