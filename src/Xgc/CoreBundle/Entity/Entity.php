<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

use Xgc\UtilsBundle\Helper\DateTime;
use Xgc\UtilsBundle\Helper\JSON;

abstract class Entity implements JSON
{
    protected $id;

    /**
     * @var DateTime
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
    public function getId(): int
    {
        return $this->id;
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

    public final function refresh()
    {
        $this->updatedAt = new DateTime();
    }
}
