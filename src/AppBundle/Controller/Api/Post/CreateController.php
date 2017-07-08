<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\Post;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\XgcSecurity;
use Xgc\InfluxBundle\Service\Influx;

class CreateController extends Controller
{

    /**
     * @param Request $request
     * @param Influx $influx
     * @param Security $security
     * @param BlogService $blogService
     * @return JsonResponse
     */
    public function indexAction(Request $request, Influx $influx, XgcSecurity $security, BlogService $blogService
    ): JsonResponse {
        $user  = $security->checkUser();
        $title = $request->fetch('title');
        $text  = $request->fetch('text');

        $blog = $blogService->create($user, $title, $text);
    }
}
