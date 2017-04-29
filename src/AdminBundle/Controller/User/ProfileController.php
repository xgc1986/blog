<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

class ProfileController extends Controller
{
    /**
     * @Route("/profile")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return [];
    }
}
