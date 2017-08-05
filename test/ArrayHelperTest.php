<?php
namespace NeedleProject\RefMat;

use PHPUnit_Framework_TestCase as TestCase;

class ArrayHelperTest extends TestCase
{
    /**
     * @dataProvider provideTrueScenarios
     * @param $array
     * @param $keys
     */
    public function testHasKey($array, $keys)
    {
        $arrayHelper = new ArrayHelper();
        $this->assertTrue(
            $arrayHelper->hasKeysInDepth($array, $keys)
        );
    }

    /**
     * @dataProvider provideFalseScenarios
     * @param $array
     * @param $keys
     */
    public function testFailHasKey($array, $keys)
    {
        $arrayHelper = new ArrayHelper();
        $this->assertFalse(
            $arrayHelper->hasKeysInDepth($array, $keys)
        );
    }

    /**
     * @return array
     */
    public function provideTrueScenarios()
    {
        return [
            [
                [
                    'foo' => [
                        'bar' => [
                            'baz' => [
                                'qux' => 'Lorem ipsum'
                            ]
                        ]
                    ]
                ],
                ['foo','bar','baz','qux']
            ],
            [
                [[[['a' => ['bar']]]]],
                [0, 0, 0, 'a', 0]
            ]
        ];
    }

    /**
     * @return array
     */
    public function provideFalseScenarios()
    {
        return [
            [
                [
                    'foo' => [
                        'foo' => [
                            'foo' => [
                                'foo' => [
                                    'bar' => 'Lorem ipsum'
                                ]
                            ]
                        ]
                    ]
                ],
                ['foo','foo','foo','foo','foo','bar']
            ]
        ];
    }
}
