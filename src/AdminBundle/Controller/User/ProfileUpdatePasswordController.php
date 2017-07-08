<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Exception\Http\PreconditionFailedException;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\XgcSecurity;

class ProfileUpdatePasswordController extends Controller
{

    /**
     * @Route("/profile/password/update")
     * @Method({"POST"})
     * @param Request $request
     * @param XgcSecurity $security
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, XgcSecurity $security)
    {
        $password     = $request->fetch('password');
        $newPassword  = $request->fetch('new-password');
        $user         = $security->checkUser();

        if (!$security->hasPassword($user, $password)) {
            throw new PreconditionFailedException("Password is not correct");
        }

        $security->changePasswords($user, $newPassword);
        $this->addFlash('notice', 'Tu contraseña ha sido actualizado correctamente');

        return $this->redirectToRoute('admin_user_profile_index');
    }
}
