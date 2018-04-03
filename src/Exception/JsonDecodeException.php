<?php
namespace Wojciech\Json\Exception;

class JsonDecodeException extends \InvalidArgumentException
{
    /** @var string */
    private $json;

    /** @var int */
    private $error;

    /** @var string */
    private $errorMessage;

    public function __construct(int $error, string $errorMessage, string $json)
    {
        $this->json = $json;
        $this->error = $error;
        $this->errorMessage = $errorMessage;

        parent::__construct('Unable to decode JSON: ' . $errorMessage);
    }

    public function getJson(): string
    {
        return $this->json;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
