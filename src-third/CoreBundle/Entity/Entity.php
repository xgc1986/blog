<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Xgc\UtilsBundle\Helper\DateTime;
use Xgc\UtilsBundle\Helper\JSON;

abstract class Entity implements JSON
{
    protected $id;

    /**
     * @var DateTime
     * @Assert\DateTime()
     */
    protected $createdAt;

    /**
     * @var DateTime
     * @Assert\DateTime()
     */
    protected $updatedAt;

    function __toArray(): array
    {
        return [
            'id' => $this->getId(),
        ];
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function __prePersist()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    public function __preUpdate()
    {
        $this->setUpdatedAt(new DateTime());
    }

    /**
     * @Assert\Callback()
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function __validate(ExecutionContextInterface $context, $payload)
    {
        $this->validate($context, $payload);
    }

    /**
     * @param $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
    }
}
