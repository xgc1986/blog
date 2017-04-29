<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Exception;

use Exception;
use Xgc\CoreBundle\Exception\DefaultExceptionHandler;
use Xgc\CoreBundle\Helper\SymfonyHelper;
use Xgc\CoreBundle\Test\KernelTestCase;

/**
 * @codeCoverageIgnore
 */
class DefaultExceptionHandlerTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
        SymfonyHelper::getInstance()->setKernel(self::$kernel);
    }

    public function testLoad()
    {
        $apiHandler = new DefaultExceptionHandler();
        $apiHandler->setContainer(self::$kernel->getContainer());
        self::assertNotNull($apiHandler);

        return $apiHandler;
    }

    /**
     * @depends testLoad
     * @param DefaultExceptionHandler $handler
     * @return DefaultExceptionHandler
     */
    public function testGetResponse(DefaultExceptionHandler $handler)
    {
        self::assertNull($handler->getResponse());

        return $handler;
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testMissingParam(DefaultExceptionHandler $handler)
    {
        $handler->throwMissingParam('test');
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testPreconditionFailed(DefaultExceptionHandler $handler)
    {
        $handler->throwPreconditionFailed();
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testUnsupportedParam(DefaultExceptionHandler $handler)
    {
        $handler->throwUnsupportedParam('foo', ['var', 'other']);
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testInvalidParam(DefaultExceptionHandler $handler)
    {
        $handler->throwInvalidParam('foo');
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testCustomInvalidParam(DefaultExceptionHandler $handler)
    {
        $handler->throwInvalidParam('foo', "Custom invalid param 'foo'");
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testAccessDenied(DefaultExceptionHandler $handler)
    {
        $handler->throwAccessDenied();
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testCustomAccessDenied(DefaultExceptionHandler $handler)
    {
        $handler->throwAccessDenied("Custom access denied");
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testInsufficentAccountPermissions(DefaultExceptionHandler $handler)
    {
        $handler->throwInsufficientAccountPermissions();
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testCustomInsufficentAccountPermissions(DefaultExceptionHandler $handler)
    {
        $handler->throwInsufficientAccountPermissions("Custom account does not have sufficient permissions");
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testAccountDissabled(DefaultExceptionHandler $handler)
    {
        $handler->throwAccountDissabled();
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testCustomAccountDissabled(DefaultExceptionHandler $handler)
    {
        $handler->throwAccountDissabled("Custom account is dissabled");
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testResourceNotFound(DefaultExceptionHandler $handler)
    {
        $handler->throwResourceNotFound('foo');
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testPageNotFound(DefaultExceptionHandler $handler)
    {
        $handler->throwPageNotFound();
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testMethodNotAllowed(DefaultExceptionHandler $handler)
    {
        $handler->throwMethodNotAllowed("PATH");
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testRequestBodyTooLarge(DefaultExceptionHandler $handler)
    {
        $handler->throwRequestBodyTooLarge();
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\CssSelector\Exception\InternalErrorException
     */
    public function testInternalServerError(DefaultExceptionHandler $handler)
    {
        $handler->throwInternalServerError(new Exception('Internal error'));
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testResourceNoLongerAvailable(DefaultExceptionHandler $handler)
    {
        $handler->throwResourceNoLongerAvailable('foo');
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testResourceAlreadyExists(DefaultExceptionHandler $handler)
    {
        $handler->throwResourceAlreadyExists('foo', 'bar');
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testAccountBeingCreated(DefaultExceptionHandler $handler)
    {
        $handler->throwAccountBeingCreated();
    }

    /**
     * @depends testGetResponse
     * @param DefaultExceptionHandler $handler
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testAccountAlreadyExists(DefaultExceptionHandler $handler)
    {
        $handler->throwAccountAlreadyExists();
    }

}
