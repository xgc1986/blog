<?php
declare(strict_types=1);
namespace AdminBundle\Controller;

use PackageVersions\Versions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;

class VersionController extends Controller
{

    /**
     * @Route("/version")
     * @Method("GET")
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
