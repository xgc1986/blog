<?php
declare(strict_types=1);
namespace AdminBundle\Controller;

//use PackageVersions\Versions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Service\Versions;

class VersionController extends Controller
{

    /**
     * @Route("/_version")
     * @Method("GET")
     * @Security("has_role('ROLE_DEVELOPER')")
     * @Template()
     * @param Versions $versions
     * @return array
     */
    public function indexAction(Versions $versions)
    {
        return [
            'versions'     => $versions->getThirdParties(),
            'selfVersions' => $versions->getFirstParties(),
        ];
    }
}
