<?php

declare(strict_types=1);

namespace Bff\Http;

use Bff\Http\Method;
use Bff\Http\Parameters;

final class Request
{
    private $method;
    private $body;

    public function __construct(Method $method, Parameters $body)
    {
        $this->method = $method;
        $this->body = $body;
    }

    public function method() : Method
    {
        return $this->method;
    }

    public function body() : Parameters
    {
        return $this->body;
    }
}
