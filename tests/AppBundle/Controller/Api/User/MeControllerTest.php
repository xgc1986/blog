<?php
declare(strict_types=1);
namespace Test\AppBundle\Controller\Api\User;

use Xgc\CoreBundle\Test\AnonymousClient;
use Xgc\CoreBundle\Test\UserClient;
use Xgc\CoreBundle\Test\WebTestCase;

/**
 * @codeCoverageIgnore
 */
class MeControllerTest extends WebTestCase
{
    public function testMe()
    {
        self::loadKernel();
        $user = new UserClient('reg_01', '12qwQW?!', self::createClient());
        $user->logIn();
        $user->get('app_api_user_me');
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

    public function testMeFail()
    {
        $user = new AnonymousClient(self::createClient());
        $user->get(
            'app_api_user_me',
            [
                'username' => 'reg_01',
                'password' => '12qwQW?!',
            ]
        );
        self::assertEquals(
            [
                'status'  => 401,
                'message' => 'Access denied',
            ],
            $user->getResponse()
        );
    }

    public function testMeBadMethod()
    {
        $user = new UserClient('reg_01', '12qwQW?!', self::createClient());
        $user->logIn();
        $user->post('app_api_user_me');

        self::assertEquals(
            [
                'status'  => 405,
                'message' => "Method 'POST' is not allowed",
                'method'  => 'POST',
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
}
