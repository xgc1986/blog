<?php
declare(strict_types=1);
namespace AppBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends \Xgc\CoreBundle\Entity\User
{
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
}
