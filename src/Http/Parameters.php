<?php

declare(strict_types=1);

namespace Bff\Http;

final class Parameters
{
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function all() : array
    {
        return $this->data;
    }

    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return $default;
    }
}
