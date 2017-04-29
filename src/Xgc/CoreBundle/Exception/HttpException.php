<?php
declare(strict_types = 1);
namespace Xgc\CoreBundle\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Xgc\CoreBundle\Helper\SymfonyHelper;

abstract class HttpException extends \Symfony\Component\HttpKernel\Exception\HttpException
{
    protected $status;
    protected $exception;
    protected $extras;
    protected $message;

    function __construct(int $status, ?string $message, array $extras = [], ?\Throwable $exception = null)
    {
        $this->extras = $extras;
        $this->status = $status;
        $this->exception = $exception;

        if ($exception) {
            $this->extras['message'] = $this->extras['message'] ?? $message ?? $exception->getMessage();
        } else {
            $this->extras['message'] = $this->extras['message'] ?? $message ?? "Error";
        }

        $diff = [];
        foreach ($extras as $key => $value) {
            $diff["%$key%"] = $value;
        }

        $this->extras['message'] = strtr($this->extras['message'], $diff);

        $this->message = $this->extras['message'];

        parent::__construct($status, $message, $exception);

        $this->extras['status'] = $this->extras['status'] ?? $status;

        if (SymfonyHelper::getInstance()->getContainer()->getParameter('kernel.environment') !== 'prod') {
            $this->extras['exception'] = $exception ? get_class($exception) : get_class($this);
            $this->extras['stack_trace'] = $exception ? $exception->getTrace() : $this->getTrace();
        } else {
            unset($this->extras['exception']);
            unset($this->extras['stack_trace']);
        }

    }

    public function getExtras(): array
    {
        return $this->extras;
    }

    function getStatus(): int
    {
        return $this->status;
    }

    public function getException(): ?\Throwable
    {
        return $this->exception;
    }

    public function __toString()
    {
        return $this->extras['message'];
    }
}
