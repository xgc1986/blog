<?php
declare(strict_types=1);
namespace AdminBundle\Topic;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Xgc\CoreBundle\Helper\DoctrineHelper;

class AdminTopic implements TopicInterface
{

    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @return void
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $stats = $this->doctrine->getRepository("AdminBundle:AdminStats")->findAll()[0];
        $stats->setCurrentUsers($stats->getCurrentUsers() + 1);
        $this->doctrine->getManager()->flush();

        $topic->broadcast(['stats' => DoctrineHelper::getInstance()->toArray($stats)]);
    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @return void
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $stats = $this->doctrine->getRepository("AdminBundle:AdminStats")->findAll()[0];
        $stats->setCurrentUsers($stats->getCurrentUsers() - 1);
        $this->doctrine->getManager()->flush();

        $topic->broadcast(['stats' => DoctrineHelper::getInstance()->toArray($stats)]);
    }


    /**
     * This will receive any Publish requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic $topic
     * @param WampRequest $request
     * @param $event
     * @param array $exclude
     * @param array $eligible
     * @return mixed|void
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        /*
        	$topic->getId() will contain the FULL requested uri, so you can proceed based on that

            if ($topic->getId() === 'acme/channel/shout')
     	       //shout something to all subs.
        *

        $topic->broadcast([
            'msg' => $event,
        ]);*/
    }

    /**
     * Like RPC is will use to prefix the channel
     * @return string
     */
    public function getName()
    {
        return 'admin.topic';
    }
}
