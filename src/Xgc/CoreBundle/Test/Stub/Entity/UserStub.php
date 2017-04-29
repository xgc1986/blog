<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Test\Stub\Entity;

use Xgc\CoreBundle\Entity\User;

class UserStub extends User
{
    public function __construct()
    {
        parent::__construct();
        $this->id = -1;
        $this->setClientIp('127.0.0.1');
    }
}
