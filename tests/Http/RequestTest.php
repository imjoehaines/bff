<?php

declare(strict_types=1);

namespace BffTests\Http;

use Bff\Http\Url;
use Bff\Http\Method;
use Bff\Http\Request;
use Bff\Http\Parameters;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testItReturnsTheGivenMethod()
    {
        $method = Method::get();
        $url = Url::from('http://example.com');
        $parameters = new Parameters();

        $request = new Request(
            $method,
            $url,
            $parameters
        );

        $this->assertSame($method, $request->method());
    }

    public function testItReturnsTheGivenUrl()
    {
        $method = Method::get();
        $url = Url::from('http://example.com');
        $parameters = new Parameters();

        $request = new Request(
            $method,
            $url,
            $parameters
        );

        $this->assertSame($url, $request->url());
    }

    public function testItReturnsTheGivenParameters()
    {
        $method = Method::get();
        $url = Url::from('http://example.com');
        $parameters = new Parameters();

        $request = new Request(
            $method,
            $url,
            $parameters
        );

        $this->assertSame($parameters, $request->body());
    }
}
