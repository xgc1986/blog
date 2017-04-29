<?php
declare(strict_types=1);
namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xgc\CoreBundle\Entity\Entity;

/**
 * Class AdminStats
 * @ORM\Entity()
 */
class AdminStats extends Entity
{

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $currentUsers;

    function __construct(int $id)
    {
        $this->id = $id;
        $this->currentUsers = 0;
    }

    public function __toArray(): array
    {
        $ret = parent::__toArray();
        $ret['users'] = $this->getCurrentUsers();

        return $ret;
    }

    /**
     * @return int
     */
    public function getCurrentUsers(): int
    {
        return $this->currentUsers;
    }

    /**
     * @param int $currentUsers
     */
    public function setCurrentUsers(int $currentUsers)
    {
        $this->currentUsers = $currentUsers;
    }
}
