<?php
declare(strict_types=1);
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Xgc\CoreBundle\DataFixtures\ORM\Fixture;

/**
 * @codeCoverageIgnore
 */
class LoadRole extends Fixture
{
    public function loadProd(): void
    {
        $role = new Role();
        $role->setRole('ROLE_USER');
        $this->persist($role, 'role-user');

        $role = new Role();
        $role->setRole('ROLE_ADMIN');
        $this->persist($role, 'role-admin');

        $role = new Role();
        $role->setRole('ROLE_DEVELOPER');
        $this->persist($role, 'role-developer');
    }

    public function loadDev(): void
    {
        $this->loadProd();
    }

    public function loadTest(): void
    {
        $this->loadProd();
    }
}
