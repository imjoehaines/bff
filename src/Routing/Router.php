<?php

declare(strict_types=1);

namespace Bff\Routing;

use Bff\Http\Method;
use Bff\Http\Request;
use Bff\Http\Response;
use BadMethodCallException;

final class Router
{
    private $routes = [];

    public function match(Method $method, string $path) : RouteHandler
    {
        $method = (string) $method;

        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }

        if (!isset($this->routes[$method])) {
            return $this->notFound();
        }

        foreach ($this->routes[$method] as $regex => $handler) {
            $matches = [];

            if (preg_match($regex, $path, $matches) === 1) {
                $variables = array_map(function (string $value) {
                    return is_numeric($value)
                    ? $value + 0 // + 0 to preserve floats
                    : $value;
                }, array_slice($matches, 1));

                return $handler->withVariables($variables);
            }
        }

        return $this->notFound();
    }

    private function notFound() : RouteHandler
    {
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
