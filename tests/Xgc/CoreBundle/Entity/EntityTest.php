<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Xgc\CoreBundle\Helper\DoctrineHelper;
use Xgc\CoreBundle\Test\Stub\Entity\EntityStub;

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
        $entity = new EntityStub;

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
        $array = [
            'id' => -1,
        ];
        self::assertEquals($array, DoctrineHelper::getInstance()->toArray($entity));
    }
}
