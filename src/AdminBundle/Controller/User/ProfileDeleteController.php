<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\XgcSecurity;

/**
 * Class ProfileDeleteController
 * @package AdminBundle\Controller\User
 */
class ProfileDeleteController extends Controller
{
    /**
     * @Route("/profile/delete")
     * @Method({"POST", "DELETE"})
     * @param Request $request
     * @param XgcSecurity $security
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request, XgcSecurity $security)
    {
        $password = $request->fetch('password');
        $user     = $security->checkUser();

        if ($security->hasPassword($user, $password)) {
            $security->deleteUser($this->getUser(), $password);
        }

        return $this->redirectToRoute('admin_user_login_index');
    }
}
