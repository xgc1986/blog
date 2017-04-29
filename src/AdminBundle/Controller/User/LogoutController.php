<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

class LogoutController extends Controller
{
    /**
     * @Route("/logout")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $this->get('xgc.security')->logout();
        return $this->redirectToRoute('admin_user_login_index');
    }
}
