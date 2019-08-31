<?php declare(strict_types=1);

namespace BffExample;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

final class IndexAction implements RequestHandlerInterface
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
        $response = $this->responseFactory->createResponse();

        $body = $response->getBody();

        $class = __CLASS__;

        $body->write(<<<HTML
            <h1>Hello world!</h1>

            <p>Bff example: <code>{$class}</code></p>
        HTML);

        return $response;
    }
}
