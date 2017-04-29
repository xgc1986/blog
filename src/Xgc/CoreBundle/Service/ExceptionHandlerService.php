<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Xgc\CoreBundle\Exception\DefaultExceptionHandler;
use Xgc\CoreBundle\Exception\ExceptionHandler;

class ExceptionHandlerService
{
    protected $container;

    protected $request;

    public function __construct(ContainerInterface $container, RequestService $request)
    {
        $this->container = $container;
        $this->request = $request;
    }

    public function getCurrentExceptionHandler(): ExceptionHandler
    {
        $handlers = $this->container->getParameter('xgc.exceptions');

        $exceptionHandler = new DefaultExceptionHandler();

        foreach ($handlers as $handler) {
            if ($this->request->getHost() === $handler['host']) {
                $exceptionHandler = new $handler['handler'];
            }
        }

        $exceptionHandler->setContainer($this->container);

        return $exceptionHandler;
    }
}