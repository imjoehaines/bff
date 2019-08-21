<?php declare(strict_types=1);

namespace Bff;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Nyholm\Psr7\Factory\Psr17Factory as DefaultPsr17Factory;

final class App
{
    private $router;
    private $requestFactory;
    private $uriFactory;

    public function __construct(
        Router $router,
        ?ServerRequestFactoryInterface $requestFactory = null,
        UriFactoryInterface $uriFactory = null
    ) {
        $this->router = $router;
        $this->requestFactory = $this->fetchRequestFactory($requestFactory);
        $this->uriFactory = $this->fetchUriFactory($uriFactory);
    }

    public function run(array $serverParameters): ResponseInterface
    {
        // TODO check these first?
        $uri = $this->uriFactory->createUri($serverParameters['REQUEST_URI']) ?? '');
        $method = $serverParameters['REQUEST_METHOD'] ?? '';

        $request = $this->requestFactory->createServerRequest(
            $method,
            $uri,
            $serverParameters
        );

        /** @var RequestHandlerInterface $requestHandler */
        $requestHandler = $this->router->match($method, $uri);

        return $requestHandler->handle($request);
    }

    private function fetchRequestFactory(
        ?ServerRequestFactoryInterface $maybeRequestFactory
    ): ServerRequestFactoryInterface {
        if ($maybeRequestFactory instanceof ServerRequestFactoryInterface) {
            return $maybeRequestFactory;
        }

        if (class_exists(DefaultPsr17Factory::class)) {
            return new DefaultPsr17Factory();
        }

        throw new RuntimeException(sprintf(
            'You must pass an instance of "%s" or run `composer install nyholm/psr7`',
            ServerRequestFactoryInterface::class
        ));
    }

    private function fetchUriFactory(
        ?UriFactoryInterface $maybeUriFactory
    ): UriFactoryInterface {
        if ($maybeUriFactory instanceof UriFactoryInterface) {
            return $maybeUriFactory;
        }

        if (class_exists(DefaultPsr17Factory::class)) {
            return new DefaultPsr17Factory();
        }

        throw new RuntimeException(sprintf(
            'You must pass an instance of "%s" or run `composer install nyholm/psr7`',
            UriFactoryInterface::class
        ));
    }
}
