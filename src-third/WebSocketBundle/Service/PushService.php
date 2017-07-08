<?php
declare(strict_types=1);

namespace WebSocketBundle\Service;

use Gos\Bundle\WebSocketBundle\Pusher\PusherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NotificationService
 * @package WebSocketBundle\Service
 */
class PushService
{
    /**
     * @var PusherInterface
     */
    protected $pusher;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * PushService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->pusher = $container->get('gos_web_socket.wamp.pusher');
    }

    /**
     * @param string $topic
     * @param array  $message
     * @param array  $params
     * @return bool
     */
    public function push(string $topic, array $message, array $params = []): bool
    {
        $this->pusher->push($message, $topic, $params);
        return true;
    }
}
