<?php declare(strict_types=1);

namespace Bff;

use RuntimeException;

final class DefaultConfiguration
{
    public function getServerParameters(): array
    {
        return $_SERVER;
    }

    public function getHeaders(): array
    {
        return getallheaders();
    }

    public function getCookies(): array
    {
        return $_COOKIE;
    }

    public function getQueryParameters(): array
    {
        return $_GET;
    }

    public function getPostParameters(): array
    {
        return $_POST;
    }

    public function getUploadedFiles(): array
    {
        return $_FILES;
    }

    public function getRawBody()
    {
        $stream = fopen('php://input', 'rb');

        if ( ! is_resource($stream)) {
            throw new RuntimeException('Unable to open "php://input" for reading');
        }

        return $stream;
    }
}
