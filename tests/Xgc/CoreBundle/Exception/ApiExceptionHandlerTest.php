<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Exception;

use Exception;
use Xgc\CoreBundle\Exception\Api\ApiException;
use Xgc\CoreBundle\Exception\ApiExceptionHandler;
use Xgc\CoreBundle\Helper\SymfonyHelper;
use Xgc\CoreBundle\Test\KernelTestCase;

/**
 * @codeCoverageIgnore
 */
class ApiExceptionHandlerTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
        SymfonyHelper::getInstance()->setKernel(self::$kernel);
    }

    public function testLoad()
    {
        $apiHandler = new ApiExceptionHandler();
        $apiHandler->setContainer(self::$kernel->getContainer());
        self::assertNotNull($apiHandler);

        return $apiHandler;
    }

    /**
     * @depends testLoad
     * @param ApiExceptionHandler $handler
     * @return ApiExceptionHandler
     */
    public function testGetResponse(ApiExceptionHandler $handler)
    {
        self::assertNull($handler->getResponse());

        return $handler;
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testMissingParam(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwMissingParam('test');
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Missing param 'test'",
                    'param'   => "test",
                    'status'  => 400,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomMissingParam(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwMissingParam('custom', "Custom missing param '%param%'");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom missing param 'custom'",
                    'param'   => "custom",
                    'status'  => 400,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testPreconditionFailed(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwPreconditionFailed();
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Precondition Failed",
                    'status'  => 412,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomPreconditionFailed(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwPreconditionFailed('Custom Precondition Failed');
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom Precondition Failed",
                    'status'  => 412,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testUnsupportedParam(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwUnsupportedParam('foo', ['var', 'other']);
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Unsupported param 'foo'",
                    'param'   => 'foo',
                    'status'  => 400,
                    'allowed' => ['var', 'other'],
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomUnsupportedParam(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwUnsupportedParam('foo', ['var', 'other'], "Custom unsupported param 'foo'");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom unsupported param 'foo'",
                    'param'   => 'foo',
                    'status'  => 400,
                    'allowed' => ['var', 'other'],
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testInvalidParam(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwInvalidParam('foo');
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Invalid param 'foo'",
                    'param'   => 'foo',
                    'status'  => 400,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomInvalidParam(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwInvalidParam('foo', "Custom invalid param 'foo'");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom invalid param 'foo'",
                    'param'   => 'foo',
                    'status'  => 400,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testAccessDenied(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwAccessDenied();
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Access denied",
                    'status'  => 401,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomAccessDenied(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwAccessDenied("Custom access denied");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom access denied",
                    'status'  => 401,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testInsufficentAccountPermissions(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwInsufficientAccountPermissions();
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Account does not have sufficient permissions",
                    'status'  => 403,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomInsufficentAccountPermissions(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwInsufficientAccountPermissions("Custom account does not have sufficient permissions");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom account does not have sufficient permissions",
                    'status'  => 403,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testAccountDissabled(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwAccountDissabled();
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Account is dissabled",
                    'status'  => 403,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomAccountDissabled(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwAccountDissabled("Custom account is dissabled");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom account is dissabled",
                    'status'  => 403,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testResourceNotFound(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwResourceNotFound('foo');
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message'  => "foo not found",
                    'status'   => 404,
                    'resource' => 'foo',
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomResourceNotFound(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwResourceNotFound('foo', "Custom %resource% not found");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message'  => "Custom foo not found",
                    'status'   => 404,
                    'resource' => 'foo',
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testPageNotFound(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwPageNotFound();
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Page not found",
                    'status'  => 404,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomPageNotFound(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwPageNotFound("Custom page not found");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom page not found",
                    'status'  => 404,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testMethodNotAllowed(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwMethodNotAllowed("PATH");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Method 'PATH' is not allowed",
                    'status'  => 405,
                    'method'  => 'PATH',
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomMethodNotAllowed(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwMethodNotAllowed('PATH', "Custom '%method%' is not allowed");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom 'PATH' is not allowed",
                    'status'  => 405,
                    'method'  => 'PATH',
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testRequestBodyTooLarge(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwRequestBodyTooLarge();
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Request body is too large",
                    'status'  => 413,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomRequestBodyTooLarge(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwRequestBodyTooLarge("Custom request body is too large");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom request body is too large",
                    'status'  => 413,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testInternalServerError(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwInternalServerError(new Exception('Internal error'));
        } catch (ApiException $exc) {
            $resp = json_decode($exc->getResponse()->getContent(), true);
            self::assertTrue(is_array($resp));
            self::assertArrayHasKey('message', $resp);
            self::assertEquals('Internal error', $resp['message']);
            self::assertArrayHasKey('status', $resp);
            self::assertEquals(500, $resp['status']);
            self::assertArrayHasKey('trace', $resp);
            self::assertTrue(is_array($resp['trace']));

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomInternalServerError(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwInternalServerError(new Exception("Internal error"), 'Custom internal error');
        } catch (ApiException $exc) {
            $resp = json_decode($exc->getResponse()->getContent(), true);
            self::assertTrue(is_array($resp));
            self::assertArrayHasKey('message', $resp);
            self::assertEquals('Custom internal error', $resp['message']);
            self::assertArrayHasKey('status', $resp);
            self::assertEquals(500, $resp['status']);
            self::assertArrayHasKey('trace', $resp);
            self::assertTrue(is_array($resp['trace']));

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testResourceNoLongerAvailable(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwResourceNoLongerAvailable('foo');
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message'  => "The resource 'foo' is no longer available",
                    'status'   => 410,
                    'resource' => 'foo',
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomResourceNoLongerAvailable(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwResourceNoLongerAvailable('foo', "Custom the resource is no longer available");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message'  => "Custom the resource is no longer available",
                    'status'   => 410,
                    'resource' => 'foo',
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testResourceAlreadyExists(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwResourceAlreadyExists('foo', 'bar');
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message'  => "foo 'bar' already exists",
                    'status'   => 409,
                    'resource' => 'foo',
                    'value'    => 'bar',
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomResourceAlreadyExists(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwResourceAlreadyExists('foo', 'bar', "Custom the %resource% (%value%) already exists");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message'  => "Custom the foo (bar) already exists",
                    'status'   => 409,
                    'resource' => 'foo',
                    'value'    => 'bar',
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testAccountBeingCreated(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwAccountBeingCreated();
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "The account is not activated",
                    'status'  => 409,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomAccountBeingCreated(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwAccountBeingCreated("Custom the account is not activated");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom the account is not activated",
                    'status'  => 409,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testAccountAlreadyExists(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwAccountAlreadyExists();
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Account already exists",
                    'status'  => 409,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }

    /**
     * @depends testGetResponse
     * @param ApiExceptionHandler $handler
     */
    public function testCustomAccountAlreadyExists(ApiExceptionHandler $handler)
    {
        try {
            $handler->throwAccountAlreadyExists("Custom account already exists");
        } catch (ApiException $exc) {
            self::assertEquals(
                [
                    'message' => "Custom account already exists",
                    'status'  => 409,
                ],
                json_decode($exc->getResponse()->getContent(), true)
            );

            return;
        }
        self::fail("No ApiExceptionRaised");
    }
}
