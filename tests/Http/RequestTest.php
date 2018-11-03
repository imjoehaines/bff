<?php

declare(strict_types=1);

namespace BffTests\Http;

use Bff\Http\Method;
use Bff\Http\Request;
use Bff\Http\Parameters;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testItReturnsTheGivenMethod() : void
    {
        $method = Method::get();
        $parameters = new Parameters();

        $request = new Request(
            $method,
            $parameters
        );

        $this->assertSame($method, $request->method());
    }

    public function testItReturnsTheGivenParameters() : void
    {
        $method = Method::get();
        $parameters = new Parameters();

        $request = new Request(
            $method,
            $parameters
        );

        $this->assertSame($parameters, $request->body());
    }
}
