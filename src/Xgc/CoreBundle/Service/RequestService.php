<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Xgc\CoreBundle\Exception\ExceptionHandler;
use Xgc\CoreBundle\Exception\Http\MissingParamException;
use Xgc\CoreBundle\Exception\Http\UnsupportedParamException;

class RequestService
{
    protected $request;
    protected $container;
    protected $firewall;

    /**
     * @var ExceptionHandler
     */
    public $http;

    /**
     * @var HeaderBag
     */
    public $headers;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->headers = $requestStack->getCurrentRequest()->headers;
    }

    public function setExceptionHandler(ExceptionHandlerService $handler): void
    {
        $this->http = $handler->getCurrentExceptionHandler();
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null|string|bool|double|int|object
     */
    public function get(string $name, $default = null)
    {
        return $this->request->get($name) ?? $default;
    }

    /**
     * @param string $name
     * @return bool|float|int|mixed|null|object|string
     * @throws MissingParamException
     */
    public function check(string $name)
    {
        $ret = $this->get($name);
        if ($ret === null) {
            throw new MissingParamException($name);
        }

        return $ret;
    }

    public function getFile(string $file): ?File
    {
        return $this->request->files->get($file) ?? null;
    }

    public function checkFile(string $file): File
    {
        $theFile = $this->getFile($file);
        if (!$theFile) {
            throw new MissingParamException($file);
        }

        return $theFile;
    }

    public function all(): array
    {
        return array_merge($this->request->query->all(), $this->request->request->all());
    }

    public function checkParams(array $allowed): void
    {
        $allowed[] = '_method';
        $result = array_diff(array_keys($this->all()), $allowed);
        if (!empty($result)) {
            throw new UnsupportedParamException($result[0], $allowed);
        }
    }

    public function getHost(): string
    {
        return $this->request->getHost();
    }

    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    public function getUri(): string
    {
        return $this->request->getUri();
    }

    public function getIp()
    {
        return $this->request->getClientIp();
    }

    public function getCurrentRequest(): Request
    {
        return $this->request;
    }
}
