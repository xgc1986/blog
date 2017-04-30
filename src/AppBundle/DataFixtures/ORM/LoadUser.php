<?php
declare(strict_types=1);
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Xgc\CoreBundle\DataFixtures\ORM\Fixture;
use Xgc\UtilsBundle\Helper\DateTime;

/**
 * @codeCoverageIgnore
 */
class LoadUser extends Fixture
{
    public function loadProd(): void
    {
        $user = new User;
        $user->setUsername('xgc1986');
        $user->setPassword('1234');
        $user->setEmail("xgc1986@gmail.com");
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->setClientIp("127.0.0.1");
        $this->persist($user);

        $user = new User;
        $user->setUsername('xgc1987');
        $user->setPassword('1234');
        $user->setEmail("xgc1987@gmail.com");
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->setClientIp("127.0.0.1");
        $this->persist($user);
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

        return $user;
    }
}
