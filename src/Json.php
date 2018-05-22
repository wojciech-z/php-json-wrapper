<?php
namespace Wojciech\Json;

use Wojciech\Json\Exception\JsonDecodeException;

use function {
    json_decode,
    json_encode,
    json_last_error_msg,
    json_last_error
};

class Json
{
    /** @return self */
    public static function static(): self
    {
        static $self;

        if (!$self) {
            $self = new self;
        }

        return $self;
    }

    /**
     * @throws \InvalidArgumentException
     * @param string $data
     * @return mixed
     */
    public function decode(string $data)
    {
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
    public function encode($data): string
    {
        return json_encode($data);
    }
}
