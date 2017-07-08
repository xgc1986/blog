<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\Post;

use AppBundle\Entity\User;
use AppBundle\Service\PostService;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;
use Xgc\CoreBundle\Service\Request;
use Xgc\CoreBundle\Service\XgcSecurity;
use Xgc\InfluxBundle\Entity\Log;
use Xgc\InfluxBundle\Service\Influx;

class CreateController extends Controller
{

    /**
     * @param Request $request
     * @param Influx $influx
     * @param XgcSecurity $security
     * @param PostService $postService
     * @return JsonResponse
     */
    public function indexAction(Request $request, Influx $influx, XgcSecurity $security, PostService $postService
    ): JsonResponse {

        /** @var User $user */
        $user  = $security->checkUser();
        $title = $request->fetch('title');
        $text  = $request->fetch('text');

        $post = $postService->create($user, $title, $text);

        $log = new Log();
        $log->setLevel(Log::INFO);
        $log->setUsername($user->getUsername());
        $log->setMessage("New post created: '$title'");
        $log->setTag("NEW POST");

        $influx->write([$log]);

        return new JsonResponse([
            'post' => $post
        ]);
    }
}
