<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Xgc\CoreBundle\Exception\ExceptionHandler;

class ExceptionHandlerService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->request   = $container->get('request');
    }

    public function getCurrentExceptionHandler(): ExceptionHandler
    {
        /** @var ExceptionHandler $exceptionHandler */
        $handler          = ($this->container->getParameter('xgc.default_exception_handler'));
        $exceptionHandler = new $handler();
        $handlers         = $this->container->getParameter('xgc.exceptions');

        foreach ($handlers as $handler) {
            if ($this->request->getHost() === $handler['host']) {
                $exceptionHandler = new $handler['handler']();
                break;
            }
        }
        $exceptionHandler->setContainer($this->container);

        return $exceptionHandler;
    }
}
