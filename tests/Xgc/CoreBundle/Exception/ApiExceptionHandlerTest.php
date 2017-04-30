<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Exception;

use Exception;
use Xgc\CoreBundle\Exception\Api\ApiException;
use Xgc\CoreBundle\Exception\ApiExceptionHandler;
use Xgc\CoreBundle\Exception\Http\AccessDeniedException;
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
}
