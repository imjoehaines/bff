<?php

declare(strict_types=1);

namespace Bff\Routing;

class RouteHandler
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
        // $this->handler() results in a fatal so we have to wrap it in parens
        // so PHP can figure out how to call $this->handler
        return ($this->handler)($request, new Respond, ...$this->dependencies);
    }
}
