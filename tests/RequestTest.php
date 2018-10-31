<?php

declare(strict_types=1);

namespace BffTests;

use Bff\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testItReturnsGivenMethod() : void
    {
        $request = new Request('GET', 'http://example.com', [], []);

        $this->assertSame('GET', $request->getMethod());
    }

    public function testItReturnsPathFromGivenUrl() : void
    {
        $request = new Request('GET', 'http://example.com/hello/friends', [], []);

        $this->assertSame('/hello/friends', $request->getPath());
    }

    public function testItReturnsGivenQueryStringParameters() : void
    {
        $request = new Request('GET', 'http://example.com', ['a' => 'b'], []);

        $this->assertSame(['a' => 'b'], $request->getQueryParamters());
    }

    public function testItCanReturnValueOfASpecificQueryStringParameter() : void
    {
        $request = new Request('GET', 'http://example.com', ['a' => 'b', 'c' => 'd'], []);

        $this->assertSame('b', $request->getQueryParamter('a'));
    }

    public function testItReturnsNullWhenQueryStringParameterDoesNotExist() : void
    {
        $request = new Request('GET', 'http://example.com', ['a' => 'b', 'c' => 'd'], []);

        $this->assertSame(null, $request->getQueryParamter('hello'));
    }

    public function testItReturnsGivenBody() : void
    {
        $request = new Request('GET', 'http://example.com', [], ['a' => 'b']);

        $this->assertSame(['a' => 'b'], $request->getBody());
    }
}
