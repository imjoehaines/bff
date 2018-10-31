<?php

declare(strict_types=1);

namespace BffTests;

use Bff\Respond;
use Bff\Response;
use PHPUnit\Framework\TestCase;

class RespondTest extends TestCase
{
    public function testItReturnsAResponse() : void
    {
        $response = (new Respond)([]);

        $this->assertSame(
            Response::class,
            get_class($response)
        );
    }
}
