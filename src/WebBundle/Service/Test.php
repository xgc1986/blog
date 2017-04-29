<?php
declare(strict_types=1);
namespace WebBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use WebBundle\Entity\Notice;

class Test implements ConsumerInterface
{
    private $doctrine;

    function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function execute(AMQPMessage $msg)
    {
        $ent = new Notice();
        $ent->setMessage($msg->getBody());
        $this->doctrine->getManager()->persist($ent);
        $this->doctrine->getManager()->flush();
    }

}
