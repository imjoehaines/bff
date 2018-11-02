<?php

declare(strict_types=1);

namespace Bff\Http;

use Bff\Http\Url;
use Bff\Http\Method;
use Bff\Http\Parameters;

interface RequestInterface
{
    public function method() : Method;

    public function url() : Url;

    public function body() : Parameters;
}
