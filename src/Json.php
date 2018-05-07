<?php
namespace Wojciech\Json;

use Wojciech\Json\Exception\JsonDecodeException;

class Json
{
    /**
     * @throws \InvalidArgumentException
     * @param string $data
     * @return mixed
     */
    public static function decode(/*string*/ $data)
    {
        if (!is_scalar($data)) {
            throw new \InvalidArgumentException('Data must be a scalar');
        }

        $decoded = json_decode($data, true);

        $lastError = json_last_error();
        if ($lastError !== JSON_ERROR_NONE) {
            throw new JsonDecodeException($lastError, json_last_error_msg(), $data);
        }

        return $decoded;
    }

    /**
     * @param mixed $data
     * @return string
     */
    public static function encode($data)
    {
        return json_encode($data);
    }
}
