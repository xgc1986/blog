<?php
declare(strict_types=1);
namespace AdminBundle\Controller;

use AppBundle\Entity\Setting;
use PackageVersions\Versions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Service\Doctrine;

class SettingsController extends Controller
{

    /**
     * @Route("/_settings")
     * @Method("GET")
     * @Security("has_role('ROLE_DEVELOPER')")
     * @Template()
     * @param Doctrine $doctrine
     * @return array
     */
    public function indexAction(Doctrine $doctrine)
    {
        $settings = $doctrine->getRepository(Setting::class)->findBy([], ['key' => 'ASC']);
        return [
            'settings' => $settings
        ];
    }
}
