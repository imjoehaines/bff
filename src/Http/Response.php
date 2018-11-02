<?php

declare(strict_types=1);

namespace Bff\Http;

use Bff\JsonException;

class Response
{
    private $body;
    private $statusCode;

    public function __construct(array $body = [], int $statusCode = 200)
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
    }

    public function withBody(array $body)
    {
        return new static($body, $this->statusCode);
    }

    public function withStatusCode(int $statusCode)
    {
        return new static($this->body, $statusCode);
    }

    public function statusCode() : int
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
