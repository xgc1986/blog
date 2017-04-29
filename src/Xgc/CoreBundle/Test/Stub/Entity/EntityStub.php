<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Test\Stub\Entity;

use Xgc\CoreBundle\Entity\Entity;

class EntityStub extends Entity
{
    public function __construct()
    {
        $this->id = -1;
    }
}
