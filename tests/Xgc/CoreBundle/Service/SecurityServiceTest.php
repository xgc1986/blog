<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Xgc\CoreBundle\Exception\Http\AccessDeniedException;
use Xgc\CoreBundle\Exception\Http\AccountBeingCreatedException;
use Xgc\CoreBundle\Exception\Http\AccountDissabledException;
use Xgc\CoreBundle\Helper\SymfonyHelper;
use Xgc\CoreBundle\Test\KernelTestCase;

class SecurityServiceTest extends KernelTestCase
{

    public function testService(): SecurityService
    {
        self::loadKernel();
        $this->stubRequest();
        $doctrine = self::$kernel->getContainer()->get('doctrine');
        $request = self::$kernel->getContainer()->get('xgc.request');
        $token = self::$kernel->getContainer()->get('security.token_storage');
        $event = self::$kernel->getContainer()->get('event_dispatcher');
        $encoder = self::$kernel->getContainer()->get('security.password_encoder');
        $secret = self::$kernel->getContainer()->getParameter('secret');

        $service = new SecurityService($doctrine, $request, $token, $event, $encoder, $secret);

        self::assertTrue(true);

        return $service;
    }

    /**
     * @depends testService
     * @param SecurityService $service
     * @return SecurityService
     */
    public function testLoginRemember(SecurityService $service): SecurityService
    {
        $user = $service->login("AppBundle:User", 'api', 'reg_01', '12qwQW?!', true);
        self::assertNotNull($user);

        return $service;
    }

    /**
     * @depends testService
     * @param SecurityService $service
     * @return SecurityService
     */
    public function testLogin(SecurityService $service): SecurityService
    {
        $user = $service->login("AppBundle:User", 'api', 'reg_01', '12qwQW?!', false);
        self::assertNotNull($user);

        return $service;
    }

    /**
     * @depends testService
     * @param SecurityService $service
     */
    public function testLoginBadUsername(SecurityService $service)
    {
        self::loadKernel();
        try {
            $service->login("AppBundle:User", 'api', 'reg_-1', '12qwQW?!', false);
        } catch (AccessDeniedException $exc) {
            self::assertArraySubset(
                [
                    'status'  => 401,
                    'message' => 'Access denied',
                ],
                $exc->getExtras()

            );

            return;
        }

        self::fail(
            "Failed asserting that exception of type '\\Xgc\\CoreBundle\\Exception\\Api\\ApiException' is thrown."
        );
    }

    /**
     * @depends testService
     * @param SecurityService $service
     */
    public function testLoginBadPassword(SecurityService $service)
    {
        self::loadKernel();
        try {
            $service->login("AppBundle:User", 'api', 'reg_01', '****', false);
        } catch (AccessDeniedException $exc) {
            self::assertArraySubset(
                [
                    'status'  => 401,
                    'message' => 'Access denied',
                ],
                $exc->getExtras()
            );

            return;
        }

        self::fail(
            "Failed asserting that exception of type '\\Xgc\\CoreBundle\\Exception\\Api\\ApiException' is thrown."
        );
    }

    /**
     * @depends testService
     * @param SecurityService $service
     */
    public function testLoginNotEnabled(SecurityService $service)
    {
        self::loadKernel();
        try {
            $service->login("AppBundle:User", 'api', 'reg_03', '12qwQW?!', false);
        } catch (AccountBeingCreatedException $exc) {
            self::assertArraySubset(
                [
                    'status'  => 409,
                    'message' => 'The account is not activated',
                ],
                $exc->getExtras()
            );

            return;
        }

        self::fail(
            "Failed asserting that exception of type '\\Xgc\\CoreBundle\\Exception\\Api\\ApiException' is thrown."
        );
    }

    /**
     * @depends testService
     * @param SecurityService $service
     */
    public function testLoginLocked(SecurityService $service)
    {
        self::loadKernel();
        try {
            $service->login("AppBundle:User", 'api', 'reg_04', '12qwQW?!', false);
        } catch (AccountDissabledException $exc) {
            self::assertArraySubset(
                [
                    'status'  => 403,
                    'message' => 'Account is dissabled',
                ],
                $exc->getExtras()
            );

            return;
        }

        self::fail(
            "Failed asserting that exception of type '\\Xgc\\CoreBundle\\Exception\\Api\\ApiException' is thrown."
        );
    }

    /**
     * @depends testLogin
     * @param SecurityService $service
     * @return SecurityService
     */
    public function testCheckUser(SecurityService $service)
    {
        $user = $service->checkUser();
        self::assertNotNull($user);

        return $service;
    }


    private function stubRequest()
    {
        $kernel = self::$kernel;
        $kernel->getContainer()->get('request_stack')->push(new Request());
        $request = new Class($kernel->getContainer()->get('request_stack')) extends RequestService
        {
            public function getHost(): string
            {
                return "api.localhost";
            }
        };
        self::$kernel->getContainer()->set('xgc.request', $request);

        SymfonyHelper::getInstance()->setKernel(self::$kernel);
    }

}
