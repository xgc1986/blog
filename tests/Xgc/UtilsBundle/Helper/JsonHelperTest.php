<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Helper;

use Xgc\CoreBundle\Entity\Entity;
use Xgc\CoreBundle\Test\KernelTestCase;
use Xgc\UtilsBundle\Helper\JSON;
use Xgc\UtilsBundle\Helper\JsonHelper;

class JsonHelperTest extends KernelTestCase
{
    public function testToArray()
    {

        $arr1 = new Class extends Entity
        {
            public function __getType(): string
            {
                return "arr1";
            }

            public function getId(): int
            {
                return 6;
            }

            public function __toArray(): array
            {
                $arr2 = new Class implements JSON
                {

                    public function __getType(): string
                    {
                        return 'arr2';
                    }

                    public function __toArray(): array
                    {
                        return [
                            'aa' => 'a1',
                            'ab' => 'a2',
                        ];
                    }

                    public function getId(): int
                    {
                        return 1;
                    }
                };

                $arr3 = [
                    [
                        'ba' => 'b1',
                        'bb' => 'b2',
                    ],
                ];

                return [
                    'a' => $arr2,
                    'b' => [
                        $this,
                        $arr2,
                    ],
                    'c' => null,
                    'd' => 3,
                    'e' => json_decode(json_encode($arr3)),
                ];
            }
        };

        $result = [];
        JsonHelper::getInstance()->encode($arr1, $result, 'arr1');
        self::assertEquals(
            [
                'arr1'     => [
                    '__id'   => 6,
                    '__type' => 'arr1',

                    'a' => [
                        '__id'   => 1,
                        '__type' => 'arr2',
                    ],
                    'b' => [
                        [
                            '__id'   => 6,
                            '__type' => 'arr1',
                        ],
                        [
                            '__id'   => 1,
                            '__type' => 'arr2',
                        ],
                    ],
                    'c' => null,
                    'd' => 3,
                    'e' => [
                        [
                            'ba' => 'b1',
                            'bb' => 'b2',
                        ],
                    ],
                ],
                'included' => [
                    'arr1' => [
                        6 => [
                            '__id'   => 6,
                            '__type' => 'arr1',

                            'a' => [
                                '__id'   => 1,
                                '__type' => 'arr2',
                            ],
                            'b' => [
                                [
                                    '__id'   => 6,
                                    '__type' => 'arr1',
                                ],
                                [
                                    '__id'   => 1,
                                    '__type' => 'arr2',
                                ],
                            ],
                            'c' => null,
                            'd' => 3,
                            'e' => [
                                [
                                    'ba' => 'b1',
                                    'bb' => 'b2',
                                ],
                            ],
                        ],
                    ],
                    'arr2' => [
                        1 => [
                            '__id'   => 1,
                            '__type' => 'arr2',
                            'aa'     => 'a1',
                            'ab'     => 'a2',
                        ],
                    ],
                ],
            ],
            $result
        );
    }
}
