<?php

declare(strict_types=1);

namespace Bff\Http;

final class Method
{
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString() : string
    {
        return $this->value;
    }

    public static function get() : Method
    {
        return new static('GET');
    }

    public static function post() : Method
    {
        return new static('POST');
    }

    public static function put() : Method
    {
        return new static('PUT');
    }

    public static function patch() : Method
    {
        return new static('PATCH');
    }

    public static function delete() : Method
    {
        return new static('DELETE');
    }
}
