<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use stdClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpFoundation\Session\Session;
use Throwable;
use Xgc\CoreBundle\Entity\Entity;
use Xgc\CoreBundle\Exception\Http\InvalidParamException;
use Xgc\CoreBundle\Exception\Http\MissingParamException;
use Xgc\CoreBundle\Exception\Http\ResourceNotFoundException;
use Xgc\UtilsBundle\Helper\DateTime;
use Xgc\UtilsBundle\Helper\Text;

class Request
{

    /**
     * @var BaseRequest
     */
    protected $original;

    /**
     * @var HeaderBag
     */
    public $headers;

    /**
     * @var Symfony
     */
    public $symfony;

    /**
     * @var Doctrine
     */
    protected $doctrine;

    /**
     * @var int
     */
    protected $delay;

    /**
     * Request constructor.
     * @param ContainerInterface $container
     */
    function __construct(ContainerInterface $container)
    {
        $this->original = $container->get('request_stack')->getCurrentRequest();
        $this->headers  = $this->original->headers;
        $this->symfony  = $container->get('symfony');
        $this->doctrine = $container->get('doctrine');
        if ($this->symfony->getEnv() === 'test') {
            $this->delay = 10;
        } else {
            $this->delay = (new DateTime())->getTime() - intval($_SERVER['REQUEST_TIME']);
        }
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->original->getMethod();
    }

    /**
     * @param string $param
     * @return bool
     */
    public function has(string $param): bool
    {
        return $this->original->query->has($param) || $this->original->request->has($param);
    }

    /**
     * @param string $param
     * @param $default
     * @return mixed
     */
    public function opt(string $param, $default = null)
    {
        if (Text::startsWith($param, "__")) {
            if ($this->symfony->getEnv() === "prod") {
                return $default;
            }
        }

        return $this->original->get($param, $default);
    }

    /**
     * @param string $param
     * @param int|null $default
     * @return int|null
     */
    public function optInt(string $param, ?int $default = null): ?int
    {
        if ($this->has($param)) {
            try {
                return intval($this->opt($param));
            } catch (Throwable $e) {
                throw new InvalidParamException($param);
            }
        }

        return $default;
    }

    /**
     * @param string $param
     * @param float|null $default
     * @return float|null
     */
    public function optFloat(string $param, ?float $default = null): ?float
    {
        if ($this->has($param)) {
            try {
                return floatval($this->opt($param));
            } catch (Throwable $e) {
                throw new InvalidParamException($param);
            }
        }

        return $default;
    }

    /**
     * @param string $param
     * @param bool|null $default
     * @return bool|null
     */
    public function optBool(string $param, ?bool $default = null): ?bool
    {
        if ($this->has($param)) {
            $res = $this->opt($param);
            if ($res === "true" || $res === "1") {
                return true;
            } else if ($res === "false" || $res === "0") {
                return false;
            } else {
                throw new InvalidParamException($param);
            }
        }

        return $default;
    }

    /**
     * @param string $param
     * @param DateTime|null $default
     * @return DateTime|null
     */
    public function optDateTime(string $param, ?DateTime $default = null): ?DateTime
    {
        $time = $this->optInt($param);
        if ($time !== null) {
            return DateTime::fromFormat('U', $time);
        }

        return $default;
    }

    /**
     * @param string $param
     * @return stdClass|null
     */
    public function optObject(string $param): ?stdClass
    {
        if ($this->has($param)) {
            $ret = json_decode($this->opt($param));
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    return $ret;
                default:
                    throw new InvalidParamException($param);
            }
        }

        return null;
    }

    /**
     * @param string $param
     * @return array|null
     */
    public function optArray(string $param): ?array
    {
        if ($this->has($param)) {
            $ret = json_decode($this->opt($param), true);
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    return $ret;
                default:
                    throw new InvalidParamException($param);
            }
        }

        return null;
    }

    /**
     * @param string $param
     * @param array|null $default
     * @return array|null
     */
    public function optList(string $param, ?array $default = null): ?array
    {
        if ($this->has($param)) {
            $res = $this->opt($param);
            if (is_iterable($res)) {
                return $res;
            }
        }

        return $default;
    }

    /**
     * @param string $file
     * @return UploadedFile
     */
    public function optFile(string $file): ?UploadedFile
    {
        return $this->original->files->get($file);
    }

    /**
     * @param string $fqn
     * @param string $name
     * @param string $arg
     * @return Entity|null
     */
    public function optEntity(string $fqn, string $name, string $arg = ''): ?Entity
    {
        $id = $this->optInt($name);

        if ($id) {
            if ($arg) {
                return $this->doctrine->getRepository($fqn)->findOneBy([$arg, $id]);
            } else {
                return $this->doctrine->getRepository($fqn)->find($id);
            }
        }

        return null;
    }

    /**
     * @param string $fqn
     * @param string $name
     * @param string $arg
     * @return array
     */
    public function optEntities(string $fqn, string $name, string $arg): array
    {
        $id = $this->opt($name);

        return $this->doctrine->getRepository($fqn)->findBy([$arg, $id]);
    }

    /**
     * @param string $param
     * @return string
     */
    public function fetch(string $param): string
    {
        $val = $this->opt($param, null);
        if (!$val) {
            throw new MissingParamException($param);
        }

        return $val;
    }

    /**
     * @param string $param
     * @return int
     */
    public function fetchInt(string $param): int
    {
        try {
            return intval($this->fetch($param));
        } catch (MissingParamException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidParamException($param);
        }
    }

    /**
     * @param string $param
     * @return float
     */
    public function fetchFloat(string $param): float
    {
        try {
            return floatval($this->fetch($param));
        } catch (MissingParamException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new InvalidParamException($param);
        }
    }

    /**
     * @param string $param
     * @return bool
     */
    public function fetchBool(string $param): bool
    {
        $res = $this->fetch($param);
        if ($res === "true" || $res === "1") {
            return true;
        } else if ($res === "false" || $res === "0") {
            return true;
        } else {
            throw new InvalidParamException($param);
        }
    }

    /**
     * @param string $param
     * @return DateTime
     */
    public function fetchDateTime(string $param): DateTime
    {
        $time = $this->fetchInt($param);

        return DateTime::fromFormat('U', $time);
    }

    /**
     * @param string $param
     * @return stdClass
     */
    public function fetchObject(string $param): stdClass
    {
        $ret = json_decode($this->fetch($param));
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $ret;
            default:
                throw new InvalidParamException($param);
        }
    }

    /**
     * @param string $param
     * @return array
     */
    public function fetchArray(string $param): array
    {
        $ret = json_decode($this->fetch($param), true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $ret;
            default:
                throw new InvalidParamException($param);
        }
    }

    /**
     * @param string $param
     * @return array
     */
    public function fetchList(string $param): array
    {
        $res = $this->fetchList($param);
        if (is_iterable($res)) {
            return $res;
        } else {
            throw new InvalidParamException($param);
        }
    }

    /**
     * @param string $file
     * @return UploadedFile
     */
    public function fetchFile(string $file): UploadedFile
    {
        $res = $this->optFile($file);
        if (!$res) {
            throw new MissingParamException($file);
        }

        return $res;
    }

    /**
     * @param string $fqn
     * @param string $name
     * @param string $arg
     * @return Entity|null
     */
    public function fetchEntity(string $fqn, string $name, string $arg = ''): Entity
    {
        $id = $this->fetch($name);

        if ($arg) {
            $ret = $this->doctrine->getRepository($fqn)->findOneBy([$arg => $id]);
        } else {
            $ret = $this->doctrine->getRepository($fqn)->find($id);
        }

        if (!$ret) {
            throw new ResourceNotFoundException($name);
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function getIP(): string
    {
        if ($this->symfony->getEnv() === 'test') {
            return "127.0.0.1";
        }

        return $this->original->getClientIp();
        //return $_SERVER["HTTP_X_REAL_IP"] ?? $_SERVER["REMOTE_ADDR"] ?? "0.0.0.0";
    }

    /**
     * @param string $key
     * @param mixed $param
     */
    public function addParam(string $key, $param)
    {
        if ($this->getMethod() === "GET" || $this->getMethod() === "DELETE") {
            $this->original->query->add([$key => $param]);
        } else {
            $this->original->request->add([$key => $param]);
        }
    }

    public function getSession(): Session
    {
        return $this->original->getSession();
    }

    public function getDelay(): int
    {
        return $this->delay;
    }

    public function getHost(): string
    {
        return $this->original->getHost();
    }
}
