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
        // TODO this is supposed to work in PHP FPM so it should work under NGINX (since PHP 7.3) but
        //      this needs to be tested
        /** @noinspection PhpComposerExtensionStubsInspection */
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

    /**
     * @return resource
     */
    public function getRawBody()
    {
        $stream = fopen('php://input', 'rb');

        if ( ! is_resource($stream)) {
            throw new RuntimeException('Unable to open "php://input" for reading');
        }

        return $stream;
    }
}
