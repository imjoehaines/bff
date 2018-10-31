<?php

declare(strict_types=1);

namespace Bff;

class Response
{
    private $body;
    private $statusCode;

    public function __construct(array $body, int $statusCode)
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    public function __toString() : string
    {
        $json = json_encode($this->body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(
                'The given JSON could not be encoded: ' . json_last_error_msg()
            );
        }

        return $json;
    }
}
