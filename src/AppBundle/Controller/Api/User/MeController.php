<?php
declare(strict_types=1);
namespace AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Helper\DoctrineHelper;
use Xgc\CoreBundle\HttpFoundation\JsonResponse;

class MeController extends Controller
{
    public function indexAction(): JsonResponse
    {
        $user = $this->checkUser();

        $me = [];
        $this->toJson($user, $me, 'user');
        return new JsonResponse($me);
    }
}
