<?php

declare(strict_types=1);

namespace Bff\Http;

use InvalidArgumentException;

class Url
{
    private $path;

    // TODO do we need any of these?
    private function __construct(/*$scheme, $basicAuth, $host, $port, */$path/*, $query, $fragment*/)
    {
        $this->path = $path;
    }

    public function path() : string
    {
        return $this->path;
    }

    public static function from(string $url) : Url
    {
        $parsedUrl = parse_url($url);

        if ($parsedUrl === false) {
            throw new InvalidArgumentException('The given string "' . $url . '" is not a valid URL');
        }

        $scheme = $parsedUrl['scheme'] ?? null;

        if ($scheme !== 'http' && $scheme !== 'https') {
            throw new InvalidArgumentException('The given string "' . $url . '" uses a non-HTTP or HTTPS scheme');
        }

        if (!isset($parsedUrl['host'])) {
            throw new InvalidArgumentException('The given string "' . $url . '" is missing a host');
        }

        $host = $parsedUrl['host'];

        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';

        $basicAuth = '';

        if (isset($parsedUrl['user']) && isset($parsedUrl['pass'])) {
            $basicAuth = $parsedUrl['user'] . '@' . $parsedUrl['pass'];
        }

        if (isset($parsedUrl['pass'])) {
            throw new InvalidArgumentException('The given string "' . $url . '" gave a password with no user');
        }

        $path = $parsedUrl['path'] ?? '/';

        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';

        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        // TODO do we need any of these?
        return new static(/*$scheme, $basicAuth, $host, $port, */$path/*, $query, $fragment*/);
    }
}
