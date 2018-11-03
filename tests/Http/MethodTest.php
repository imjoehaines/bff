<?php

declare(strict_types=1);

namespace BffTests\Http;

use Bff\Http\Method;
use PHPUnit\Framework\TestCase;

class MethodTest extends TestCase
{
    /**
     * @dataProvider methods
     *
     * @param string $function
     * @param string $expected
     * @return void
     */
    public function testItConstructsCorrectly(string $function, string $expected) : void
    {
        $this->assertSame(
            $expected,
            (string) Method::$function()
        );
    }

    public function methods() : array
    {
        return [
            'GET' => ['get', 'GET'],
            'POST' => ['post', 'POST'],
            'PUT' => ['put', 'PUT'],
            'PATCH' => ['patch', 'PATCH'],
            'DELETE' => ['delete', 'DELETE'],
        ];
    }
}
