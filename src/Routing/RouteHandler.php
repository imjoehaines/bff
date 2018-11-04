<?php

declare(strict_types=1);

namespace Bff\Routing;

use Bff\Http\Request;
use Bff\Http\Response;

final class RouteHandler
{
    private $handler;
    private $dependencies;
    private $variables = [];

    public function __construct(callable $handler, array $dependencies = [])
    {
        $this->handler = $handler;
        $this->dependencies = $dependencies;
    }

    public function withVariables(array $variables) : RouteHandler
    {
        $other = clone $this;
        $other->variables = $variables;

        return $other;
    }

    public function handle(Request $request) : Response
    {
        return ($this->handler)(
            $request,
            new Response(),
            ...$this->variables,
            ...$this->dependencies
        );
    }
}
