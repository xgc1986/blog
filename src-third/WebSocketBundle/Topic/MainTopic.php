<?php
declare(strict_types=1);

namespace WebSocketBundle\Topic;

use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Xgc\CoreBundle\Entity\User;

/**
 * Class MainTopic
 * @package WebSocketBundle\Topic
 */
class MainTopic implements TopicInterface
{

    protected $container;

    /**
     * MainTopic constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic               $topic
     * @param WampRequest          $request
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $id   = $this->getInt($request, 'user');
        $user = $this->getUser();

        if (!$user || $user->getId() !== $id) {
            $topic->remove($connection);

            return;
        }
    }

    /**
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param WampRequest         $request
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //this will broadcast the message to ALL subscribers of this topic.
        //$topic->broadcast(['msg' => $connection->resourceId . " has left " . $topic->getId()]);
    }

    /**
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param WampRequest         $request
     * @param                     $event
     * @param array               $exclude
     * @param array               $eligible
     */
    public function onPublish(
        ConnectionInterface $connection,
        Topic $topic,
        WampRequest $request,
        $event,
        array $exclude,
        array $eligible
    )
    {
        $topic->broadcast($event);
    }

    /**
     * Like RPC is will use to prefix the channel
     * @return string
     */
    public function getName()
    {
        return 'user.feed';
    }

    /**
     * @param string $word
     * @return bool
     */
    private function isVar(string $word): bool
    {
        $out = [];
        preg_match("/{[a-z0-9A-Z]+}/", $word, $out);

        return count($out) > 0;
    }

    /**
     * @param WampRequest $request
     * @param string      $param
     * @param int         $default
     * @return int
     */
    private function getInt(WampRequest $request, string $param, int $default = 0): int
    {
        $pattern = explode('/', $request->getRoute()->getPattern());
        $input   = explode('/', $request->getMatched());

        for ($i = 0; $i < count($pattern); $i++) {
            if ($this->isVar($pattern[$i])) {
                $str = substr($pattern[$i], 1, -1);
                if ($str === $param) {
                    return intval($input[$i]);
                }
            }

        }

        return intval($default);
    }

    /**
     * @return null|User
     */
    private function getUser(): ?User
    {
        $user = $this->container->get('security')->getUser();

        return $this->container->get('doctrine')->getRepository("UserBundle:User")->find($user->getId());
    }
}
