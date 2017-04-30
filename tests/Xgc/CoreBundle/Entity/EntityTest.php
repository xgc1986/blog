<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Xgc\CoreBundle\Test\Stub\Entity\EntityStub;
use Xgc\UtilsBundle\Helper\JsonHelper;

/**
 * @codeCoverageIgnore
 */
class EntityTest extends KernelTestCase
{
    protected function setUp()
    {
    }

    function testNewEntity(): EntityStub
    {
        $entity = new EntityStub();

        self::assertEquals(-1, $entity->getId());

        return $entity;
    }

    /**
     * @depends testNewEntity
     *
     * @param EntityStub $entity
     */
    function testToArray(EntityStub $entity)
    {
        $result = [];

        $array = [
            'result'    => [
                'id'     => -1,
                '__type' => 'entity_stub',
                '__id'   => -1,
            ],
            '__included' => [
                'entity_stub' => [
                    -1 => [
                        'id'     => -1,
                        '__type' => 'entity_stub',
                        '__id'   => -1,
                    ],
                ],
            ],
        ];
        self::assertEquals($array, JsonHelper::getInstance()->encode($entity, $result, 'result'));
    }
}
