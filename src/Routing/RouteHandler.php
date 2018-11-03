<?php

declare(strict_types=1);

namespace Bff\Routing;

use Bff\Http\Request;
use Bff\Http\Response;

final class RouteHandler
{
    private $handler;
    private $dependencies;

    public function __construct(callable $handler, array $dependencies = [])
    {
        $this->handler = $handler;
        $this->dependencies = $dependencies;
    }

    public function handle(Request $request) : Response
    {
        return ($this->handler)($request, new Response, ...$this->dependencies);
    }
}
