<?php

declare(strict_types=1);

namespace BffTests\Http;

use Bff\Http\Parameters;
use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase
{
    public function testItAcceptsAnArrayOfData() : void
    {
        $parameters = new Parameters(['a' => 1, 'b' => 2]);

        $this->assertSame(
            ['a' => 1, 'b' => 2],
            $parameters->all()
        );
    }

    public function testItReturnsTrueIfAKeyIsPresent() : void
    {
        $parameters = new Parameters(['a' => 1, 'b' => 2]);

        $this->assertSame(
            true,
            $parameters->has('a')
        );
    }

    public function testItReturnsTrueIfAKeyIsPresentAndValueIsNull() : void
    {
        $parameters = new Parameters(['a' => 1, 'b' => 2, 'c' => null]);

        $this->assertSame(
            true,
            $parameters->has('c')
        );
    }

    public function testItReturnsFalseIfAKeyIsNotPresent() : void
    {
        $parameters = new Parameters(['a' => 1, 'b' => 2]);

        $this->assertSame(
            false,
            $parameters->has('a key that does not exist')
        );
    }

    public function testItReturnsValueForAGivenKey() : void
    {
        $parameters = new Parameters(['a' => 1, 'b' => 2]);

        $this->assertSame(
            1,
            $parameters->get('a')
        );
    }
}