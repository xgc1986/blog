<?php
declare(strict_types=1);
namespace AdminBundle\Controller\Log;

use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Paginator\Paginator;
use Xgc\InfluxBundle\Entity\Log;

class ListController extends Controller
{
    /**
     * @Route("/_log")
     * @Template()
     * @Security("has_role('ROLE_DEVELOPER')")
     * @Method({"GET"})
     */
    public function indexAction(): array
    {
        $influx = $this->get('xgc.influx');

        $page  = intval($this->request->get('p', 0));
        $orderType = $this->request->get('ot', 'ASC');
        $size = $this->get('xgc.settings')->getInt('paginator.size', 10);

        $log = $influx->read(Log::class);

        return [
            'logs' => $log
        ];
    }
}
