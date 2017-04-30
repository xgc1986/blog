<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Exception\HttpException;

class ProfileUpdatePasswordController extends Controller
{
    /**
     * @Route("/profile/password")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

    }

    /**
     * @Route("/profile/password/update")
     * @Method({"POST"})
     */
    public function updateAction()
    {
        $password = $this->request->check('password');
        $newPassword = $this->request->check('new-password');
        $newPassword2 = $this->request->check('new-password2');

        try {
            $this->get('xgc.security')->changePasswords($password, $newPassword, $newPassword2);
        } catch (HttpException $exception) {
            $this->addFlash(
                'error',
                $exception->getMessage()
            );

            return $this->render('@Admin/User/ProfileUpdatePassword/index.html.twig');
        }

        $this->addFlash(
            'notice',
            'Tu contraseña ha sido actualizado correctamente'
        );

        return $this->redirectToRoute('admin_user_profile_index');
    }
}
