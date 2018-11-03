<?php

declare(strict_types=1);

namespace Bff\Routing;

use Bff\Http\Url;
use Bff\Http\Method;
use Bff\Http\Request;
use Bff\Http\Response;
use BadMethodCallException;

class Router
{
    private $routes = [];

    public function match(Method $method, Url $url) : RouteHandler
    {
        if (isset($this->routes[(string) $method][$url->path()])) {
            return $this->routes[(string) $method][$url->path()];
        }

        // handle no route match with a 404
        return new RouteHandler(function (Request $request, Response $response) : Response {
            return $response->withStatusCode(404);
        });
    }

    public function get(string $path, callable $handler, array $dependencies = []) : Router
    {
        return $this->addRoute(
            Method::get(),
            $path,
            $handler,
            $dependencies
        );
    }

    public function post(string $path, callable $handler, array $dependencies = []) : Router
    {
        return $this->addRoute(
            Method::post(),
            $path,
            $handler,
            $dependencies
        );
    }

    public function put(string $path, callable $handler, array $dependencies = []) : Router
    {
        return $this->addRoute(
            Method::put(),
            $path,
            $handler,
            $dependencies
        );
    }

    public function patch(string $path, callable $handler, array $dependencies = []) : Router
    {
        return $this->addRoute(
            Method::patch(),
            $path,
            $handler,
            $dependencies
        );
    }

    public function delete(string $path, callable $handler, array $dependencies = []) : Router
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
