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

    /**
     * Provide different delimiters and token formats
     */
    public function testDifferentDelimiters()
    {
        $refMatcher = new Matcher("{%", "%}", ":");
        $inputArray = [
            "test" => "{%foo:bar%}",
            "foo"  => [
                "bar" => "result"
            ]
        ];
        $expectedOutput = [
            "test" => "result",
            "foo"  => [
                "bar" => "result"
            ]
        ];
        $matches = $refMatcher->buildSet($inputArray);

        $this->assertEquals(
            $expectedOutput,
            $matches
        );
    }

    /**
     * Provide success scenarios
     * Tied to ::testMath
     *
     * @return array
     */
    public function provideScenarios()
    {
        // more complex scenario
        $fixturePath = dirname(
            realpath(__FILE__)
        ) . DIRECTORY_SEPARATOR . 'Fixture' . DIRECTORY_SEPARATOR . 'test.yml';
        $fixtureContent = new File($fixturePath);
        $fixtureContent = $fixtureContent->getContent()->getArray();

        return [
            // first set
            [
                ['foo' => 'bar', 'baz' => '[[foo]]'],
                ['foo' => 'bar', 'baz' => 'bar']
            ],
            // second set
            [
                $fixtureContent['input'],
                $fixtureContent['output']
            ],
            [
                ['foo' => 'bar'],
                ['foo' => 'bar']
            ]
        ];
    }
}
