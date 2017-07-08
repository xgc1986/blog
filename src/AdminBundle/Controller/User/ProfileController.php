<?php
declare(strict_types=1);
namespace AdminBundle\Controller\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

/**
 * Class ProfileController
 * @package AdminBundle\Controller\User
 */
class ProfileController extends Controller
{
    /**
     * @Route("/profile")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return [];
    }
}
