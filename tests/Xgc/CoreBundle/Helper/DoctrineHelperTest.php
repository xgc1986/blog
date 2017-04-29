<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Helper;

use Xgc\CoreBundle\Entity\Entity;
use Xgc\CoreBundle\Helper\DoctrineHelper;
use Xgc\CoreBundle\Test\KernelTestCase;
use Xgc\UtilsBundle\Helper\Arrayizable;

class DoctrineHelperTest extends KernelTestCase
{
    public function testToArray()
    {

        $arr1 = new Class extends Entity
        {
            public function getId() : int
            {
                return 6;
            }

            public function __toArray(): array
            {
                $arr2 = new Class implements Arrayizable
                {

                    public function __toArray(): array
                    {
                        return [
                            'aa' => 'a1',
                            'ab' => 'a2',
                        ];
                    }
                };

                $arr3 = [
                    [
                        'ba' => 'b1',
                        'bb' => 'b2',
                    ]
                ];

                return [
                    'a' => DoctrineHelper::getInstance()->toArray($arr2),
                    'b' => [
                        DoctrineHelper::getInstance()->toArray($this),
                        DoctrineHelper::getInstance()->toArray($arr2),
                    ],
                    'c' => DoctrineHelper::getInstance()->toArray(null),
                    'd' => DoctrineHelper::getInstance()->toArray(3),
                    'e' => DoctrineHelper::getInstance()->toArray(json_decode(json_encode($arr3))),
                ];
            }
        };

        self::assertEquals([
            'a' => [
                'aa' => 'a1',
                'ab' => 'a2',
            ],
            'b' => [
                '6',
                [
                    'aa' => 'a1',
                    'ab' => 'a2',
                ]
            ],
            'c' => null,
            'd' => 3,
            'e' => [
                [
                    'ba' => 'b1',
                    'bb' => 'b2',
                ]
            ]
        ], DoctrineHelper::getInstance()->toArray($arr1));
    }
}
