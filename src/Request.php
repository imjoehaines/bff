<?php

declare(strict_types=1);

namespace Bff;

class Request
{
    private $method;
    private $url;
    private $queryParameters;
    private $body;

    public function __construct(
        string $method,
        string $url,
        array $queryParameters,
        array $body
    ) {
        $this->method = $method;
        $this->url = $url;
        $this->queryParameters = $queryParameters;
        $this->body = $body;
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function getPath() : string
    {
        return parse_url($this->url, PHP_URL_PATH);
    }

    public function getQueryParamters() : array
    {
        return $this->queryParameters;
    }

    public function getQueryParamter(string $key)
    {
        return $this->queryParameters[$key] ?? null;
    }

    public function getBody() : array
    {
        return $this->body;
    }
}
