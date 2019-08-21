<?php declare(strict_types=1);

namespace Bff;

use Psr\Http\Server\RequestHandlerInterface;

final class Router
{
    private $container;

    public function __construct(
        ContainerInterface $container,
    ) {
        $this->container = $container;
    }

    public function get(string $path, string $routeHandlerIndentifier): void
    {
        $this->addRoute('GET', $path, $routeHandlerIndentifier);
    }

    public function post(string $path, string $routeHandlerIndentifier): void
    {
        $this->addRoute('POST', $path, $routeHandlerIndentifier);
    }

    public function put(string $path, string $routeHandlerIndentifier): void
    {
        $this->addRoute('PUT', $path, $routeHandlerIndentifier);
    }

    public function patch(string $path, string $routeHandlerIndentifier): void
    {
        $this->addRoute('PATCH', $path, $routeHandlerIndentifier);
    }

    public function delete(string $path, string $routeHandlerIndentifier): void
    {
        $this->addRoute('DELETE', $path, $routeHandlerIndentifier);
    }

    private function addRoute(
        string $method,
        string $path,
        string $routeHandlerIndentifier
    ): void {
    }

    public function match(string $method, UriInterface $uri): RequestHandlerInterface
    {
    }
}
