<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

use Xgc\UtilsBundle\Helper\JSON;

abstract class Entity implements JSON
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
