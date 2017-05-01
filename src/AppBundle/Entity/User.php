<?php
declare(strict_types=1);
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Xgc\CoreBundle\Entity\Role;

class User extends \Xgc\CoreBundle\Entity\User
{

    function __construct()
    {
        parent::__construct();
        $this->roles = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    public function addRole(Role $role): User
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): User
    {
        $this->roles->remove($role);
        return $this;
    }
}
