<?php
declare(strict_types=1);
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Xgc\CoreBundle\DataFixtures\ORM\Fixture;
use Xgc\CoreBundle\Entity\Entity;
use Xgc\UtilsBundle\Helper\DateTime;

/**
 * @codeCoverageIgnore
 */
class LoadUser extends Fixture
{
    public function loadProd(): void
    {
        $user = new User;
        $user->setUsername('admin');
        $user->setPassword('1234');
        $this->getContainer()->get('xgc.entity.user')->setPassword($user, '1234');
        $user->setEmail("admin@gmail.com");
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->setClientIp("127.0.0.1");
        $user->addRole($this->getRole('role-admin'));
        $this->persist($user);

        $user = new User;
        $user->setUsername('user');
        $this->getContainer()->get('xgc.entity.user')->setPassword($user, '1234');
        $user->setEmail("user@gmail.com");
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->setClientIp("127.0.0.1");
        $user->addRole($this->getRole('role-user'));
        $this->persist($user);

        $user = new User;
        $user->setUsername('developer');
        $this->getContainer()->get('xgc.entity.user')->setPassword($user, '1234');
        $user->setEmail("developer@gmail.com");
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->setClientIp("127.0.0.1");
        $user->addRole($this->getRole('role-developer'));
        $this->persist($user);
    }

    public function loadDev(): void
    {
        $this->loadProd();
    }

    public function loadTest(): void
    {
        $this->persist($this->createUser('reg_0😀', true, false, '', ''));

        $this->persist($this->createUser('reg_01', true, false, '', ''));
        $this->persist($this->createUser('reg_02', false, true, '', ''));
        $this->persist($this->createUser('reg_03', false, false, '', ''));
        $this->persist($this->createUser('reg_04', true, true, '', ''));

        $this->persist($this->createUser('reg_05', false, false, 'A', ''));

        $this->persist($this->createUser('reg_06', true, false, '', 'A'));
        $this->persist($this->createUser('reg_07', false, true, '', 'B'));
        $this->persist($this->createUser('reg_08', false, false, '', 'C'));
        $this->persist($this->createUser('reg_09', true, true, '', 'D'));
    }

    private function createUser(string $id, bool $enabled, bool $locked, string $regToken, string $passToken): User
    {
        $user = new User;
        $user->setUsername($id);
        $this->getContainer()->get('xgc.entity.user')->setPassword($user, '12qwQW?!');
        $user->setEmail("$id@gmail.com");
        $user->setEnabled($enabled);
        $user->setLocked($locked);
        $user->setClientIp("127.0.0.1");
        if ($regToken) {
            $user->setRegisterToken(str_repeat($regToken, 32));
            $user->setRegisterTokenAt(new DateTime);
        }
        if ($passToken) {
            $user->setResetPasswordToken(str_repeat($passToken, 32));
            $user->setResetPasswordTokenAt(new DateTime);
        }
        $user->addRole($this->getRole('role-user'));

        return $user;
    }

    /**
     * @param string $role
     * @return Role|Entity
     */
    private function getRole(string $role): Role
    {
        return $this->get($role);
    }

    public function getOrder()
    {
        return 2;
    }
}
