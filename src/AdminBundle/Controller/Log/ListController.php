<?php
declare(strict_types=1);
namespace AdminBundle\Controller\Log;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\Settings;
use Xgc\InfluxBundle\Entity\Log;
use Xgc\InfluxBundle\Service\Influx;

/**
 * Class ListController
 * @package AdminBundle\Controller\Log
 */
class ListController extends Controller
{
    /**
     * @Route("/_log")
     * @Template()
     * @Security("has_role('ROLE_DEVELOPER')")
     * @Method({"GET"})
     * @param Influx $influx
     * @param Request $request
     * @param Settings $settings
     * @return array
     */
    public function indexAction(Influx $influx, Request $request, Settings $settings): array
    {
        $page  = $request->optInt('p', 0);
        $order = $request->optBool('o', true);
        $size  = $settings->getInt('paginator.size', 25);

        $log = $influx->read(Log::class, [], null, null, $order, $size, $page);

        return [
            'logs' => $log,
        ];
    }
}
