<?php declare(strict_types=1);

namespace Bff;

interface Configuration
{
    public function getServerParameters(): array;
    public function getHeaders(): array;
    public function getCookies(): array;
    public function getQueryParameters(): array;
    public function getPostParameters(): array;
    public function getUploadedFiles(): array;

    /**
     * @return resource
     */
    public function getRawBody();
}
