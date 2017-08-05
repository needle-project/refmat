<?php
namespace NeedleProject\RefMat;

use NeedleProject\FileIo\File;
use PHPUnit_Framework_TestCase as TestCase;

class MatcherTest extends TestCase
{
    /**
     * @dataProvider provideScenarios
     * @param array $inputArray
     * @param array $expectedOutput
     */
    public function testMath($inputArray, $expectedOutput)
    {
        $refMatcher = new Matcher();
        $matches = $refMatcher->buildSet($inputArray);

        $this->assertEquals(
            $expectedOutput,
            $matches
        );
    }

    public function provideScenarios()
    {
        // more complex scenario
        $fixturePath = dirname(
            realpath(__FILE__)
        ) . DIRECTORY_SEPARATOR . 'Fixture' . DIRECTORY_SEPARATOR . 'test.yml';
        $fixtureContent = new File($fixturePath);
        $fixtureContent = $fixtureContent->getContent()->getArray();
        return [
            /*// first set
            [
                ['foo' => 'bar', 'baz' => '[[foo]]'],
                ['foo' => 'bar', 'baz' => 'bar']
            ],
            // second set
            [
                [
                    'foo' =>
                    [
                        'bar' => 'baz'
                    ],
                    'qux' => '[[foo.bar]]'
                ],
                [
                    'foo' =>
                        [
                            'bar' => 'baz'
                        ],
                    'qux' => 'baz'
                ]
            ],
            // third set
            [
                [
                    'foo' =>
                        [
                            'bar' => 'baz'
                        ],
                    'baz' => '[[qux.bar]]',
                    'qux' => '[[foo]]'
                ],
                [
                    'foo' =>
                        [
                            'bar' => 'baz'
                        ],
                    'baz' => 'baz',
                    'qux' => [
                        'bar' => 'baz'
                    ]
                ]
            ],*/
            [
                $fixtureContent['input'],
                $fixtureContent['output']
            ]
        ];

    }


}