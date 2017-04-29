<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

abstract class Entity
{
    protected $id;

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
}
