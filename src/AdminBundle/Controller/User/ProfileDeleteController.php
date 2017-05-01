<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

class ProfileDeleteController extends Controller
{
    /**
     * @Route("/profile/delete")
     * @Security("has_role('ROLE_USER')")
     * @Method({"POST", "DELETE"})
     */
    public function indexAction()
    {
        $password = $this->request->check('password');
        $this->get('xgc.security')->deleteUser($this->getUser(), $password);
        return $this->redirectToRoute('admin_user_login_index');
    }
}
