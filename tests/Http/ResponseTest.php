<?php

declare(strict_types=1);

namespace BffTests\Http;

use Bff\Http\Response;
use Bff\Http\JsonException;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testItThrowsOnInvalidJson() : void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage(
            'The given JSON could not be encoded: Recursion detected'
        );

        $one = new \stdClass();
        $one->one = $one;

        $body = ['one' => $one];

        $response = new Response($body, 200);

        $response->__toString();
    }

    /**
     * @dataProvider statusCodes
     *
     * @param int $code
     * @return void
     */
    public function testItReturnsGivenStatusCode(int $code) : void
    {
        $response = new Response([], $code);

        $this->assertSame($code, $response->statusCode());
    }

    /**
     * @dataProvider jsonBodies
     *
     * @param array $body
     * @return void
     */
    public function testItCanBeCastToJsonString(array $body) : void
    {
        $response = new Response($body, 200);

        $this->assertSame(json_encode($body), $response->__toString());
    }

    public function statusCodes() : array
    {
        return array_map(function (int $code) {
            return [$code];
        }, range(100, 599));
    }

    public function jsonBodies() : array
    {
        return [
            'simple array' => [
                [1, 2, 3],
                '[1,2,3]'
            ],
            'associative array' => [
                ['one' => 1, 'two' => 'two', '3' => 'three'],
                '{"one":1,"two":"two","3":"three}'
            ],
            'associative array containing json-able objects' => [
                [
                    'one' => new \stdClass(),
                    'two' => new class implements \JsonSerializable {
                        public function jsonSerialize()
                        {
                            return ['this' => 'is', 'inside' => 'two'];
                        }
                    },
                ],
                '{"one":{},"two":{"this":"is","inside":"two"}}'
            ],
        ];
    }
}
