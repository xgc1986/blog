<?php
declare(strict_types=1);
namespace WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Xgc\CoreBundle\Entity\Entity;

/**
 * Class Notice
 * @ORM\Entity()
 */
class Notice extends Entity
{
    /**
     * @ORM\Column(type="text")
     */
    protected $message;

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
