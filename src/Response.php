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
        return json_encode($this->body);
    }
}
