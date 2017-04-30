<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Role extends Entity implements RoleInterface
{

    /**
     * @var string
     * @Assert\Length(
     *     min = 4,
     *     max = 32
     * )
     */
    protected $role;

    function __toArray(): array
    {
        $ret = parent::__toArray();
        $ret['role'] = $this->getRole();

        return $ret;
    }

    public function __getType(): string
    {
        return 'role';
    }

    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role)
    {
        $this->role = $role;
    }
}
