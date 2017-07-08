<?php
declare(strict_types=1);
namespace Xgc\InfluxBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Xgc\CoreBundle\Entity\User;
use Xgc\InfluxBundle\Entity\Log;

class Logger
{
    const INFO    = "INFO";
    const DEBUG   = "DEBUG";
    const WARNING = "WARNING";
    const ERROR   = "ERROR";

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function info(?User $user, string $tag, string $message): Log
    {
        return $this->log(self::INFO, $user, $tag, $message);
    }

    public function debug(?User $user, string $tag, string $message): Log
    {
        return $this->log(self::DEBUG, $user, $tag, $message);
    }

    public function warning(?User $user, string $tag, string $message): Log
    {
        return $this->log(self::WARNING, $user, $tag, $message);
    }

    public function error(?User $user, string $tag, string $message): Log
    {
        return $this->log(self::ERROR, $user, $tag, $message);
    }

    private function log(string $level, ?User $user, string $tag, string $message): Log
    {
        $log = new Log();
        $log->setUsername($user? $user->getUsername() : 'anon.');
        $log->setLevel($level);
        $log->setMessage($message);
        $log->setTag($tag);

        $influx = $this->container->get('xgc.influx');
        $influx->write([$log]);

        return $log;
    }

}
