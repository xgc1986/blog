<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Entity;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Xgc\CoreBundle\Entity\User;
use Xgc\CoreBundle\Helper\SymfonyHelper;
use Xgc\CoreBundle\Test\KernelTestCase;
use Xgc\CoreBundle\Test\Stub\Entity\UserStub;
use Xgc\UtilsBundle\Helper\DateTime;
use Xgc\UtilsBundle\Helper\JsonHelper;

/**
 * @codeCoverageIgnore
 */
class UserTest extends KernelTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    function testNewUser()
    {
        $user = new UserStub();

        self::assertEquals(-1, $user->getId());

        return $user;
    }

    /**
     * @depends testNewUser
     * @param User $user
     */
    function testUsername(User $user)
    {
        $user->setUsername("username");
        self::assertEquals("username", $user->getUsername());
    }

    /**
     * @depends testNewUser
     * @param User $user
     */
    function testEmail(User $user)
    {
        self::assertNotNull($user->setEmail("xgc1986@gmail.com"));
        self::assertEquals("xgc1986@gmail.com", $user->getEmail());
    }

    /**
     * @depends testNewUser
     * @param User $user
     */
    function testEraseCredentials(User $user)
    {
        self::assertNotNull($user->eraseCredentials());
    }

    /**
     * @depends testNewUser
     * @param User $user
     */
    function testIsAccountNonExpired(User $user)
    {
        self::assertTrue($user->isAccountNonExpired());
    }

    /**
     * @depends testNewUser
     * @param User $user
     */
    function testIsAccountNonLocked(User $user)
    {
        self::assertTrue($user->isAccountNonLocked());
        self::assertFalse($user->isLocked());
        self::assertNotNull($user->setLocked(true));
        self::assertFalse($user->isAccountNonLocked());
        self::assertTrue($user->isLocked());
    }

    /**
     * @depends testNewUser
     * @param User $user
     */
    function testEnabled(User $user)
    {
        self::assertFalse($user->isEnabled());
        self::assertNotNull($user->setEnabled(true));
        self::assertTrue($user->isEnabled());
    }

    /**
     * @depends testNewUser
     * @param User $user
     */
    function testGetRoles(User $user)
    {
        self::assertCount(0, $user->getRoles());
    }

    /**
     * @depends testNewUser
     * @param User $user
     */
    function testIsCredentialsNonExpired(User $user)
    {
        self::assertTrue($user->isCredentialsNonExpired());
    }

    /**
     * @depends testNewUser
     * @depends testUsername
     * @depends testEmail
     * @depends testEnabled
     * @depends testIsAccountNonLocked
     *
     * @param User $user
     */
    function testToArray(User $user)
    {
        self::loadKernel();
        $this->mockRequest();

        $map = [];
        JsonHelper::getInstance()->encode($user, $map, 'user');
        $array = [
            'user' => [
                'id'        => -1,
                'username'  => "username",
                'avatar'    => '/bundles/xgccore/images/avatar.jpg',
                '__type'    => 'user',
                '__id'      => -1,
            ],
        ];
        self::assertArraySubset($array, $map);
    }

    function testEasyMethods()
    {
        $user = $this->castUser(
            new Class extends User
            {
                public function getId(): int
                {
                    return 2;
                }
            }
        );

        $now = new DateTime();
        $user->setRegisterToken(str_repeat("A", 32));
        $user->setResetPasswordToken(str_repeat("B", 32));
        $user->setRegisterTokenAt($now);
        $user->setResetPasswordTokenAt($now);

        self::assertEquals($now, $user->getResetPasswordTokenAt());
        self::assertEquals($now, $user->getRegisterTokenAt());
        self::assertEquals(str_repeat("A", 32), $user->getRegisterToken());
        self::assertEquals(str_repeat("B", 32), $user->getResetPasswordToken());
    }

    protected function setUp()
    {
        self::bootKernel();
        SymfonyHelper::getInstance()->setKernel(self::$kernel);
    }

    private function castUser($obj): User
    {
        return $obj;
    }

    private function mockRequest()
    {
        $rs = new RequestStack();
        $req = self::getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        $rs->push($this->castToRequest($req));

        $req->method('getHost')
            ->willReturn("api.localhost");

        self::$kernel->getContainer()->set('request_stack', $rs);
    }

    private function castToRequest($req): Request
    {
        return $req;
    }

}
