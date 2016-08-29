<?php

declare(strict_types=1);

namespace Bff;

use Psr\Http\Message\RequestInterface as Request;

class Router
{
    private $routes = [];

    public function match(string $method, string $uri) : RouteHandler
    {
        if (isset($this->routes[$method][$uri])) {
            return $this->routes[$method][$uri];
        }

        // handle no route match with a 404
        return new RouteHandler(function (Request $request, Respond $respond) : Response {
            return $respond([], 404);
        });
    }

    public function get(string $uri, callable $handler, ...$dependencies) : Router
    {
        $this->routes = array_merge_recursive(
            $this->routes,
            ['GET' => [$uri => new RouteHandler($handler, $dependencies)]]
        );

        return $this;
    }
}
