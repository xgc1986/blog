<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

class LoginController extends Controller
{
    /**
     * @Route("/login")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return [];
    }
}
