<?php
namespace Wojciech\Json\Test;

use PHPUnit\Framework\TestCase;
use Wojciech\Json\Exception\JsonDecodeException;
use Wojciech\Json\Json;

class JsonTest extends TestCase
{
    public function testGettingStatically()
    {
        $instance = Json::static();

        self::assertInstanceOf(Json::class, $instance);
    }

    /**
     * @dataProvider provideTestCases
     */
    public function testEncode($input, $output)
    {
        $instance = new Json();

        $encoded = $instance->encode($input);

        self::assertEquals($output, $encoded);
    }

    /**
     * @dataProvider provideTestCases
     */
    public function testDecode($output, $input)
    {
        $instance = new Json();

        $encoded = $instance->decode($input);

        self::assertEquals($output, $encoded);
    }

    public function provideTestCases(): array
    {
        return [
            'null' => [
                null,
                'null'
            ],
            'true' => [
                true,
                'true'
            ],
            'false' => [
                false,
                'false'
            ],
            'empty string' => [
                '',
                '""'
            ],
            'a string' => [
                'some text here',
                '"some text here"'
            ],
            'an integer' => [
                123,
                '123'
            ],
            'a float' => [
                12.34,
                '12.34'
            ],
            'an array' => [
                ['one', 2, 3.0],
                '["one",2,3]'
            ],
            'a map' => [
                ['first' => 1, 'second' => 2],
                '{"first":1,"second":2}'
            ],
            'a structure' => [
                ['first' => [1, 'two', false], 'second' => ['sub' => 5]],
                '{"first":[1,"two",false],"second":{"sub":5}}'
            ]
        ];
    }

    public function testEncodeObject()
    {
        $object = new \stdClass();
        $object->key1 = 'value1';
        $object->key2 = 2;
        $object->key3 = true;

        $instance = new Json();

        $result = $instance->encode($object);

        self::assertEquals('{"key1":"value1","key2":2,"key3":true}', $result);
    }

    public function testEncodeJsonSerializable()
    {
        $object = new class implements \JsonSerializable {
            private $key1 = 'one';
            protected $key2 = 'two';
            public $key3 = 'three';

            public function jsonSerialize()
            {
                return 'serialized';
            }
        };

        $instance = new Json();

        $result = $instance->encode($object);

        self::assertEquals('"serialized"', $result);
    }

    /**
     * @dataProvider provideInvalid
     */
    public function testDecodeInvalid($input, $errorCode, $errorMessage)
    {
        $instance = new Json();

        try {
            $instance->decode($input);
        } catch (JsonDecodeException $e) {
            self::assertEquals($errorCode, $e->getError());
            self::assertEquals($errorMessage, $e->getErrorMessage());
        }
    }

    public function provideInvalid(): array
    {
        return [
            'string without quotes' => ['asd', JSON_ERROR_SYNTAX, 'Syntax error'],
            'missing terminating quote' => ['"abc', JSON_ERROR_CTRL_CHAR, 'Control character error, possibly incorrectly encoded'],
            'too many opened brackets' => ['[["asd"]', JSON_ERROR_SYNTAX, 'Syntax error'],
            'too many closed brackets' => ['["asd"]]', JSON_ERROR_SYNTAX, 'Syntax error'],
            'array in braces' => ['{"asd"}', JSON_ERROR_SYNTAX, 'Syntax error'],
            'mismatched braces' => ['{"asd"]', JSON_ERROR_SYNTAX, 'Syntax error']
        ];
    }

}