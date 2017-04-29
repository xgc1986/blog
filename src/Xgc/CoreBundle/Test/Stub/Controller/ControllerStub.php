<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Test\Stub\Controller;

use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Service\RequestService;

class ControllerStub extends Controller
{
    function __construct()
    {
    }

    function getRequest(): ?RequestService
    {
        return $this->request;
    }
}
