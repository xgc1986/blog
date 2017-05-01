<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Xgc\CoreBundle\Exception\Http\AccessDeniedException;
use Xgc\CoreBundle\Exception\Http\InternalErrorException;
use Xgc\CoreBundle\Exception\Http\MethodNotAllowedException;
use Xgc\CoreBundle\Exception\Http\PageNotFoundException;

abstract class ExceptionHandler
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var HttpException
     */
    protected $exception;

    public function handle(Exception $exception): void {
        if ($exception instanceof HttpException) {
            $this->exception = $exception;
        } else {
            if ($exception instanceof MethodNotAllowedHttpException) {
                $method = $this->container->get('request_stack')->getCurrentRequest()->getMethod();
                $this->exception = new MethodNotAllowedException($method, null, $exception);
            } else if ($exception instanceof NotFoundHttpException) {
                $this->exception = new PageNotFoundException(null, $exception);
            } else if ($exception instanceof \Symfony\Component\Security\Core\Exception\AccessDeniedException) {
                $this->exception = new AccessDeniedException(null, $exception);
            } else {
                $this->exception = new InternalErrorException($exception->getMessage(), $exception);
            }

        }
    }

    public function setContainer(ContainerInterface $container) {
        $this->container = $container;
    }

    abstract public function getResponse(): ?Response;


}
