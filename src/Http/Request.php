<?php

declare(strict_types=1);

namespace Bff\Http;

use Bff\Http\Url;
use Bff\Http\Method;
use Bff\Http\Parameters;

class Request
{
    private $method;
    private $url;
    private $body;

    public function __construct(Method $method, Url $url, Parameters $body)
    {
        $this->method = $method;
        $this->url = $url;
        $this->body = $body;
    }

    public function method() : Method
    {
        return $this->method;
    }

    public function url() : Url
    {
        return $this->url;
    }

    public function body() : Parameters
    {
        return $this->body;
    }
}
