<?php declare(strict_types=1);

namespace Bff;

use RuntimeException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Nyholm\Psr7\Factory\Psr17Factory as DefaultUriFactory;
use Nyholm\Psr7\Factory\Psr17Factory as DefaultStreamFactory;
use Nyholm\Psr7\Factory\Psr17Factory as DefaultServerRequestFactory;

final class App
{
    private $router;
    private $requestFactory;
    private $uriFactory;
    private $streamFactory;

    public function __construct(
        Router $router,
        ServerRequestFactoryInterface $requestFactory = null,
        UriFactoryInterface $uriFactory = null,
        StreamFactoryInterface $streamFactory = null
    ) {
        $this->router = $router;
        $this->requestFactory = $this->fetchRequestFactory($requestFactory);
        $this->uriFactory = $this->fetchUriFactory($uriFactory);
        $this->streamFactory = $this->fetchStreamFactory($streamFactory);
    }

    public function run(Configuration $configuration): ResponseInterface
    {
        $serverParameters = $configuration->getServerParameters();

        // TODO check these first?
        $uri = $this->uriFactory->createUri($serverParameters['REQUEST_URI'] ?? '');
        $method = $serverParameters['REQUEST_METHOD'] ?? '';

        $body = $this->streamFactory->createStreamFromResource($configuration->getRawBody());

        $request = $this->requestFactory->createServerRequest($method, $uri, $serverParameters)
            ->withCookieParams($configuration->getCookies())
            ->withQueryParams($configuration->getQueryParameters())
            ->withParsedBody($configuration->getPostParameters())
            ->withUploadedFiles($configuration->getUploadedFiles()) // TODO structure of this is wrong
            ->withBody($body);

        foreach ($configuration->getHeaders() as $header => $value) {
            $request = $request->withAddedHeader($header, $value);
        }

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

        if (class_exists(DefaultServerRequestFactory::class)) {
            return new DefaultServerRequestFactory();
        }

        throw new RuntimeException(
            sprintf(
                'You must pass an instance of "%s" or run `composer install nyholm/psr7`',
                ServerRequestFactoryInterface::class
            )
        );
    }

    private function fetchUriFactory(
        ?UriFactoryInterface $maybeUriFactory
    ): UriFactoryInterface {
        if ($maybeUriFactory instanceof UriFactoryInterface) {
            return $maybeUriFactory;
        }

        if (class_exists(DefaultUriFactory::class)) {
            return new DefaultUriFactory();
        }

        throw new RuntimeException(
            sprintf(
                'You must pass an instance of "%s" or run `composer install nyholm/psr7`',
                UriFactoryInterface::class
            )
        );
    }

    private function fetchStreamFactory(
        ?StreamFactoryInterface $maybeStreamFactory
    ): StreamFactoryInterface {
        if ($maybeStreamFactory instanceof StreamFactoryInterface) {
            return $maybeStreamFactory;
        }

        if (class_exists(DefaultStreamFactory::class)) {
            return new DefaultStreamFactory();
        }

        throw new RuntimeException(
            sprintf(
                'You must pass an instance of "%s" or run `composer install nyholm/psr7`',
                StreamFactoryInterface::class
            )
        );
    }
}
