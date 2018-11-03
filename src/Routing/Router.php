<?php

declare(strict_types=1);

namespace Bff\Routing;

use Bff\Http\Method;
use Bff\Http\Response;
use Bff\Routing\Request;
use BadMethodCallException;

class Router
{
    private $routes = [];

    public function match(string $method, string $path) : RouteHandler
    {
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }

        // handle no route match with a 404
        return new RouteHandler(function (Request $request, Response $response) : Response {
            return $response->withStatusCode(404);
        });
    }

    public function get(string $path, callable $handler, ...$dependencies) : Router
    {
        return $this->addRoute(
            Method::get(),
            $path,
            $handler,
            $dependencies
        );
    }

    public function post(string $path, callable $handler, ...$dependencies) : Router
    {
        return $this->addRoute(
            Method::post(),
            $path,
            $handler,
            $dependencies
        );
    }

    public function put(string $path, callable $handler, ...$dependencies) : Router
    {
        return $this->addRoute(
            Method::put(),
            $path,
            $handler,
            $dependencies
        );
    }

    public function patch(string $path, callable $handler, ...$dependencies) : Router
    {
        return $this->addRoute(
            Method::patch(),
            $path,
            $handler,
            $dependencies
        );
    }

    public function delete(string $path, callable $handler, ...$dependencies) : Router
    {
        return $this->addRoute(
            Method::delete(),
            $path,
            $handler,
            $dependencies
        );
    }

    private function addRoute(
        Method $method,
        string $path,
        callable $handler,
        array $dependencies
    ) : Router {
        $this->routes = array_merge_recursive(
            $this->routes,
            [(string) $method => [$path => new RouteHandler($handler, $dependencies)]]
        );

        return $this;
    }
}
