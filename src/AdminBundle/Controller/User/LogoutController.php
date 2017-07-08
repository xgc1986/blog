<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Service\XgcSecurity;

/**
 * Class LogoutController
 * @package AdminBundle\Controller\User
 */
class LogoutController extends Controller
{
    /**
     * @Route("/logout")
     * @Method({"GET"})
     * @param XgcSecurity $security
     * @return RedirectResponse
     */
    public function indexAction(XgcSecurity $security): RedirectResponse
    {
        $security->logout();
        return $this->redirectToRoute('admin_user_login_index');
    }
}
