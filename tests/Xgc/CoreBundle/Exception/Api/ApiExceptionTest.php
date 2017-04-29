<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Exception\Api;

use PHPUnit\Framework\TestCase;
use Xgc\CoreBundle\Exception\Api\ApiException;

/**
 * @codeCoverageIgnore
 */
class ApiExceptionTest extends TestCase
{
    public function testGetResponse()
    {
        $exc = new ApiException(300, "success", ['value' => 'foo']);
        self::assertEquals(300, $exc->getStatus());
        self::assertEquals("success", $exc->getMessage());

        $response = $exc->getResponse();
        self::assertEquals(300, $response->getStatusCode());
        self::assertEquals(
            ['value' => 'foo', 'status' => 300, 'message' => 'success'],
            json_decode($response->getContent(), true)
        );
    }

    public function testCustomMessage()
    {
        $exc = new ApiException(300, "success %value%s", ['value' => 'foo']);
        self::assertEquals(300, $exc->getStatus());
        self::assertEquals("success foos", $exc->getMessage());

        $response = $exc->getResponse();
        self::assertEquals(300, $response->getStatusCode());
        self::assertEquals(
            ['value' => 'foo', 'status' => 300, 'message' => "success foos"],
            json_decode($response->getContent(), true)
        );
    }
}
