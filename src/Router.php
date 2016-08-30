<?php

declare(strict_types=1);

namespace Bff;

use BadMethodCallException;
use Psr\Http\Message\RequestInterface as Request;

class Router
{
    private $allowedMethods = ['POST', 'GET', 'PUT', 'PATCH', 'DELETE'];
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

    public function __call(string $method, array $arguments) : Router
    {
        if (!isset($arguments[0], $arguments[1]) ||
            !is_string($arguments[0]) ||
            !is_callable($arguments[1]) ||
            !in_array(strtoupper($method), $this->allowedMethods, true)
        ) {
            throw new BadMethodCallException();
        }

        $uri = array_shift($arguments);
        $handler = array_shift($arguments);
        $dependencies = $arguments;

        $this->routes = array_merge_recursive(
            $this->routes,
            [strtoupper($method) => [$uri => new RouteHandler($handler, $dependencies)]]
        );

        return $this;
    }
}
