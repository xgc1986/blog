<?php
declare(strict_types=1);
namespace Test\AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Test\AnonymousClient;
use Xgc\CoreBundle\Test\WebTestCase;

/**
 * @codeCoverageIgnore
 */
class RegisterControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        $user->check(200);
    }

    public function testRegisterUsernameWithEmoji()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => '😀🍆💩🂡🇪🇸',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'user'   => [
                    'id'       => $this->getUserId('😀🍆💩🂡🇪🇸'),
                    'username' => '😀🍆💩🂡🇪🇸',
                    'avatar' => '/bundles/xgccore/images/avatar.jpg',
                    '__type' => 'user'
                ],
                'status' => 200,
            ],
            $user->getResponse()
        );
    }

    public function testRegisterInsecurePassword()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qwe123',
                'password2' => '123qwe123',
            ]
        );
        $user->check(400);
    }

    public function testPasswordWithEmoji()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE🍆',
                'password2' => '123qweQWE🍆',
            ]
        );
        self::assertArraySubset(
            [
                'status'  => 400,
                'message' => "Password insecure",
                'param'   => 'password',
            ],
            $user->getResponse()
        );
    }

    public function testEmailWithEmoji()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986🍆@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'status' => 200,
                'user'   => [
                    'id'       => $this->getUserId("xgc1986"),
                    'username' => 'xgc1986',
                ],
            ],
            $user->getResponse()
        );
    }

    public function testEmailWithDomainEmoji()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986🍆@🍆gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'status' => 200,
                'user'   => [
                    'id'       => $this->getUserId("xgc1986"),
                    'username' => 'xgc1986',
                ],
            ],
            $user->getResponse()
        );
    }

    public function testBadLongEmail()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => str_repeat('A', 500) . '@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'message' => "This value is too long. It should have 255 characters or less.",
                'param'   => 'email',
                'status'  => 400,
            ],
            $user->getResponse()
        );
    }

    public function testBadEmail()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986@gmailcom',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'message' => "This value is not a valid email address.",
                'param'   => 'email',
                'status'  => 400,
            ],
            $user->getResponse()
        );
    }

    public function testShortUsername()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'message' => "This value is too short. It should have 4 characters or more.",
                'param'   => 'username',
                'status'  => 400,
            ],
            $user->getResponse()
        );
    }

    public function testLongUsername()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'message' => "This value is too long. It should have 32 characters or less.",
                'param'   => 'username',
                'status'  => 400,
            ],
            $user->getResponse()
        );
    }

    public function testMissingUsername()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'message' => "Missing param 'username'",
                'status'  => 400,
                'param'   => 'username',
            ],
            $user->getResponse()
        );
    }

    public function testMissingEmail()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'message' => "Missing param 'email'",
                'status'  => 400,
                'param'   => 'email',
            ],
            $user->getResponse()
        );
    }

    public function testMissingPassword()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986@gmail.com',
                'password2' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'message' => "Missing param 'password'",
                'status'  => 400,
                'param'   => 'password',
            ],
            $user->getResponse()
        );
    }

    public function testMissingPassword2()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username' => 'xgc1986',
                'email'    => 'xgc1986@gmail.com',
                'password' => '123qweQWE',
            ]
        );
        self::assertArraySubset(
            [
                'message' => "Missing param 'password2'",
                'status'  => 400,
                'param'   => 'password2',
            ],
            $user->getResponse()
        );
    }

    public function testNoMatchPasswords()
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWW',
            ]
        );
        $user->check(412);
        self::assertArraySubset(
            [
                'message' => 'Passwords missmatch',
                'status'  => 412,
            ],
            $user->getResponse()
        );
    }

    public function testBadMethod()
    {
        $user = new AnonymousClient(self::createClient());
        $user->get(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        $user->check(405);
        self::assertArraySubset(
            [
                'message' => "Method 'GET' is not allowed",
                'status'  => 405,
                'method'  => 'GET',
            ],
            $user->getResponse()
        );
    }

    /**
     * @dataProvider usersProvider
     * @param string $name
     */
    public function testUserAlreadyExistsByUsername(string $name)
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => $name,
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        $user->check(409);
        self::assertArraySubset(
            [
                'message'  => "username '$name' already exists",
                'resource' => 'username',
                'status'   => 409,
                'value'    => $name,
            ],
            $user->getResponse()
        );
    }

    /**
     * @dataProvider usersProvider
     * @param string $name
     */
    public function testUserAlreadyExistsByEmail(string $name)
    {
        $user = new AnonymousClient(self::createClient());
        $user->post(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => "$name@gmail.com",
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );
        $user->check(409);
        self::assertArraySubset(
            [
                'message'  => "email '$name@gmail.com' already exists",
                'resource' => 'email',
                'status'   => 409,
                'value'    => "$name@gmail.com",
            ],
            $user->getResponse()

        );
    }

    public function usersProvider(): array
    {
        return [
            ['reg_01'],
            ['reg_02'],
            ['reg_03'],
            ['reg_04'],
            ['reg_05'],
            ['reg_06'],
            ['reg_07'],
            ['reg_08'],
            ['reg_09'],
        ];
    }

    private function getUserId($username)
    {
        $userRepo = self::$kernel->getContainer()->get('doctrine')->getRepository("AppBundle:User");
        $user = $userRepo->findOneBy(['username' => $username]);

        return $user ? $user->getId() : null;
    }

    public function testRegsiterBadMethod()
    {
        $user = new AnonymousClient(self::createClient());
        $user->get(
            'app_api_user_register',
            [
                'username'  => 'xgc1986',
                'email'     => 'xgc1986@gmail.com',
                'password'  => '123qweQWE',
                'password2' => '123qweQWE',
            ]
        );

        self::assertArraySubset(
            [
                'status'  => 405,
                'message' => "Method 'GET' is not allowed",
                'method'  => 'GET',
            ],
            $user->getResponse()
        );
    }
}
