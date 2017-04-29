<?php
declare(strict_types=1);
namespace Test\AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Test\AnonymousClient;
use Xgc\CoreBundle\Test\WebTestCase;

/**
 * @codeCoverageIgnore
 */
class LoginControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_login',
            [
                'username' => 'reg_01',
                'password' => '12qwQW?!',
            ]
        );

        //$user->check(200);
        self::assertEquals(
            [
                'status' => 200,
                'user'   => [
                    'email'    => "reg_01@gmail.com",
                    'id'       => $this->getUserId("reg_01"),
                    'username' => "reg_01",
                    'ip'       => '127.0.0.1',
                ],
            ],
            $user->getResponse()
        );
    }

    public function testLoginByEmail()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_login',
            [
                'username' => 'reg_01@gmail.com',
                'password' => '12qwQW?!',
            ]
        );

        //$user->check(200);
        self::assertEquals(
            [
                'status' => 200,
                'user'   => [
                    'email'    => "reg_01@gmail.com",
                    'id'       => $this->getUserId("reg_01"),
                    'username' => "reg_01",
                    'ip'       => '127.0.0.1',
                ],
            ],
            $user->getResponse()
        );
    }

    public function testLoginBadUsername()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_login',
            [
                'username' => 'reg_-1',
                'password' => '12qwQW?!',
            ]
        );
        self::assertEquals(
            [
                'message' => 'Access denied',
                'status'  => 401,
            ],
            $user->getResponse()
        );
    }

    public function testLoginBadPassword()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_login',
            [
                'username' => 'reg_01',
                'password' => '1234',
            ]
        );
        self::assertEquals(
            [
                'message' => 'Access denied',
                'status'  => 401,
            ],
            $user->getResponse()
        );
    }

    public function testLoginMissingPassword()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_login',
            [
                'username' => 'reg_01',
            ]
        );
        self::assertEquals(
            [
                'message' => "Missing param 'password'",
                'status'  => 400,
                'param'   => 'password',
            ],
            $user->getResponse()
        );
    }

    public function testLoginMissingUsername()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_login',
            [
                'password' => '12qwQW?!',
            ]
        );
        self::assertEquals(
            [
                'message' => "Missing param 'username'",
                'status'  => 400,
                'param'   => 'username',
            ],
            $user->getResponse()
        );
    }

    public function testLoginNotEnabled()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_login',
            [
                'username' => 'reg_02',
                'password' => '12qwQW?!',
            ]
        );
        self::assertEquals(
            [
                'message' => 'The account is not activated',
                'status'  => 409,
            ],
            $user->getResponse()
        );
    }

    public function testLoginLocked()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_login',
            [
                'username' => 'reg_04',
                'password' => '12qwQW?!',
            ]
        );
        self::assertEquals(
            [
                'message' => 'Account is dissabled',
                'status'  => 403,
            ],
            $user->getResponse()
        );
    }

    private function getUserId($username)
    {
        $userRepo = self::$kernel->getContainer()->get('doctrine')->getRepository("AppBundle:User");
        $user = $userRepo->findOneBy(['username' => $username]);

        return $user ? $user->getId() : null;
    }

    public function testLoginBadMethod()
    {
        $user = new AnonymousClient(self::createClient());
        $user->get(
            'app_api_user_login',
            [
                'username' => 'reg_01',
                'password' => '12qwQW?!',
            ]
        );

        self::assertEquals(
            [
                'status'  => 405,
                'message' => "Method 'GET' is not allowed",
                'method'  => 'GET',
            ],
            $user->getResponse()
        );
    }
}
