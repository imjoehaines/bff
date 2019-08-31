<?php declare(strict_types=1);

namespace Bff;

use RuntimeException;
use Psr\Http\Message\UriInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Nyholm\Psr7\Factory\Psr17Factory as DefaultResponseFactory;

final class Router
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var array
     */
    private $routes = [];

    public function __construct(
        ContainerInterface $container,
        ResponseFactoryInterface $responseFactory = null
    ) {
        $this->container = $container;
        $this->responseFactory = $this->fetchResponseFactory($responseFactory);
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
        // TODO this is largely useless as-is
        $this->routes[$method][$path] = $routeHandlerIndentifier;
    }

    public function match(string $method, UriInterface $uri): RequestHandlerInterface
    {
        $possibleRoutes = $this->routes[$method] ?? [];

        $routeHandlerIndentifier = $possibleRoutes[$uri->getPath()] ?? null;

        if ($routeHandlerIndentifier === null) {
            return $this->createNotFoundHandler($this->responseFactory);
        }

        if ( ! $this->container->has($routeHandlerIndentifier)) {
            // TODO we should almost definitely be erroring here!
            //      however we need some concept of dev/production first as in
            //      production we should handle this gracefully
            return $this->createNotFoundHandler($this->responseFactory);
        }

        return $this->container->get($routeHandlerIndentifier);
    }

    private function createNotFoundHandler(ResponseFactoryInterface $responseFactory): RequestHandlerInterface
    {
        return new class ($responseFactory) implements RequestHandlerInterface
        {
            /**
             * @var ResponseFactoryInterface
             */
            private $responseFactory;

            public function __construct(ResponseFactoryInterface $responseFactory)
            {
                $this->responseFactory = $responseFactory;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return $this->responseFactory->createResponse(404);
            }
        };
    }

    private function fetchResponseFactory(
        ?ResponseFactoryInterface $maybeResponseFactory
    ): ResponseFactoryInterface {
        if ($maybeResponseFactory instanceof ResponseFactoryInterface) {
            return $maybeResponseFactory;
        }

        if (class_exists(DefaultResponseFactory::class)) {
            return new DefaultResponseFactory();
        }

        throw new RuntimeException(
            sprintf(
                'You must pass an instance of "%s" or run `composer install nyholm/psr7`',
                ResponseFactoryInterface::class
            )
        );
    }
}
