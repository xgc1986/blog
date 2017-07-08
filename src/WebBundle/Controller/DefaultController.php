<?php
declare(strict_types=1);
namespace WebBundle\Controller;

use PackageVersions\Versions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->request->getMethod() == "POST") {
            $username = $this->request->check("username");
            $password = $this->request->check("password");
            $this->get('security')->login("AppBundle:User", "api", $username, $password, true);
        }
        return [
            'versions' => Versions::VERSIONS,
        ];
    }

    /**
     * @Route("/produce")
     * @Template()
     */
    public function produceAction()
    {
        $msg = ['user_id' => 1235, 'image_path' => '/path/to/new/pic.png'];
        $that = $this->get('old_sound_rabbit_mq.upload_picture_producer');

        $that->publish(serialize($msg));

        return new JsonResponse;
    }
}
