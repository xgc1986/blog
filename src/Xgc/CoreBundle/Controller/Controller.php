<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Xgc\CoreBundle\Entity\Entity;
use Xgc\CoreBundle\Entity\User;
use Xgc\CoreBundle\Exception\ExceptionHandler;
use Xgc\CoreBundle\Helper\DoctrineHelper;
use Xgc\CoreBundle\Service\RequestService;
use Xgc\CoreBundle\Service\SecurityService;

abstract class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    /**
     * @var RequestService
     */
    protected $request;

    /**
     * @var ExceptionHandler
     */
    protected $http;

    /**
     * @var SecurityService
     */
    protected $security;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        if ($container) {
            $this->request = $this->get('xgc.request');
            $this->http = $this->request->http;
            $this->security = $this->get('xgc.security');
        }
    }

    public function checkUser(): User
    {
        return $this->security->checkUser();
    }

    public function toArray(Entity $entity)
    {
        return DoctrineHelper::getInstance()->toArray($entity);
    }
}
