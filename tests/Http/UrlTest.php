<?php

declare(strict_types=1);

namespace BffTests\Http;

use Bff\Http\Url;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function testItCanBeConstructedFromValidUrl() : void
    {
        $url = Url::from('https://example.com');

        $this->assertSame(Url::class, get_class($url));
    }

    public function testItReturnsThePathFromTheGivenUrl() : void
    {
        $url = Url::from('https://example.com/an/example/path');

        $this->assertSame('/an/example/path', $url->path());
    }

    public function testItNormalisesMissingPaths() : void
    {
        $url = Url::from('https://example.com');

        $this->assertSame('/', $url->path());
    }

    public function testItThrowsOnInvalidUrl() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The given string "http://:80" is not a valid URL');

        Url::from('http://:80');
    }

    public function testItThrowsOnInvalidSchemes() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The given string "ssh://example.com" uses a non-HTTP or HTTPS scheme');

        Url::from('ssh://example.com');
    }
}
