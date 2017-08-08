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
     * @expectedException \InvalidArgumentException
     * @dataProvider provideExceptionScenarios
     * @param $notArray
     */
    public function testHasKeyException($notArray)
    {
       $arrayHelper = new ArrayHelper();
       $arrayHelper->hasKeysInDepth($notArray, []);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideExceptionScenarios
     * @param $notArray
     */
    public function testGetValueException($notArray)
    {
        $arrayHelper = new ArrayHelper();
        $arrayHelper->getValueFromDepth($notArray, []);
    }

    /**
     * @dataProvider provideValueScenarios
     * @param array $array
     * @param array $keys
     * @param mixed $expectedValue
     */
    public function testGetValue($array, $keys, $expectedValue)
    {
        $arrayHelper = new ArrayHelper();
        $this->assertEquals(
            $expectedValue,
            $arrayHelper->getValueFromDepth($array, $keys)
        );
    }

    /**
     * @dataProvider provideValueScenarios
     * @param array $array
     * @param array $keys
     */
    public function testFailGetValue($array, $keys)
    {
        $arrayHelper = new ArrayHelper();
        $arrayHelper->getValueFromDepth($array, $keys);
    }

    /**
     * @expectedException \NeedleProject\RefMat\Exception\NotFoundException
     */
    public function testNotFoundGetValue()
    {
        $arrayHelper = new ArrayHelper();
        $arrayHelper->getValueFromDepth(['a' => 'b'], ['a', 'c']);
    }

    /**
     * Tied to ::testHasKey
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
     * Tied to ::testFailHasKey
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
                ['foo', 'foo', 'foo', 'foo', 'foo', 'bar']
            ]
        ];
    }

    /**
     * Tied to ::testException
     * @return array
     */
    public function provideExceptionScenarios()
    {
        return [
            [1],
            ['a'],
            [new \stdClass()],
            [0xFF],
            [1.2],
            [[]],
            [['a' => 'b']]
        ];
    }

    /**
     * Tied to ::testGetValue
     * @return array
     */
    public function provideValueScenarios()
    {
        return [
            // first scenario
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
                ['foo', 'foo', 'foo', 'foo', 'bar'],
                'Lorem ipsum'
            ]
        ];
    }


    /**
     * Tied to ::testFailGetValue
     * @return array
     */
    public function provideFailValueScenarios()
    {
        return [
            // first scenario
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
                ['foo', 'foo', 'foo', 'foo', 'foo', 'bar']
            ]
        ];
    }
}
