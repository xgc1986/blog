<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Exception\HttpException;

class ProfileAvatarController extends Controller
{

    /**
     * @Route("/profile/avatar")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function indexAction()
    {

    }

    /**
     * @Route("/profile/avatar/update")
     * @Method({"POST"})
     */
    public function updateAction()
    {
        $file = $this->request->checkFile('avatar');

        $user = $this->getUser();
        $id = $user->getId();

        try {
            $this->get('xgc.entity.user')->uploadAvatar($user, $file, "/images/$id", "avatar");
        } catch (HttpException $exception) {
            $this->addFlash(
                'error',
                $exception->getMessage()
            );

            return $this->render('@Admin/User/ProfileAvatar/index.html.twig');
        }

        $this->addFlash(
            'notice',
            'Tu avatar ha sido actualizado correctamente'
        );

        return $this->redirectToRoute('admin_user_profile_index');
    }
}
