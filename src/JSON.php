<?php
namespace JSON;

use JSON\Exception\JsonDecodeException;

class JSON
{
    public static function decode(string $data)
    {
        $decoded = json_decode($data, true);

        $lastError = json_last_error();
        if ($lastError !== JSON_ERROR_NONE) {
            throw new JsonDecodeException($lastError, json_last_error_msg(), $data);
        }

        return $decoded;
    }

    public static function encode($data)
    {
        return json_encode($data);
    }
}
