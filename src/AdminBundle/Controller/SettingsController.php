<?php
declare(strict_types=1);
namespace AdminBundle\Controller;

use PackageVersions\Versions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

class SettingsController extends Controller
{

    /**
     * @Route("/_settings")
     * @Method("GET")
     * @Security("has_role('ROLE_DEVELOPER')")
     * @Template()
     */
    public function indexAction()
    {
        $settings = $this->getDoctrine()->getRepository("AppBundle:Setting")->findBy([], ['key' => 'ASC']);
        return [
            'settings' => $settings
        ];
    }
}
