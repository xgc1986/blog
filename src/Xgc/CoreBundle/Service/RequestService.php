<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->request = $container->get('request_stack')->getCurrentRequest();
    }

    public function init(): void
    {
        $this->http = $this->container->get('xgc.exception.handler')->getCurrentExceptionHandler();
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
}
