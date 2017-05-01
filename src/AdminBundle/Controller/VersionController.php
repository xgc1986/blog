<?php
declare(strict_types=1);
namespace AdminBundle\Controller;

use PackageVersions\Versions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

class VersionController extends Controller
{

    /**
     * @Route("/_version")
     * @Method("GET")
     * @Security("has_role('ROLE_DEVELOPER')")
     * @Template()
     */
    public function indexAction()
    {
        $versions = Versions::VERSIONS;
        ksort($versions);

        $selfVersions = $this->getParameter('xgc.versions');
        ksort($selfVersions);
        return [
            'versions' => $versions,
            'selfVersions' => $selfVersions,
        ];
    }
}
