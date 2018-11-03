<?php

declare(strict_types=1);

namespace BffTests\Routing;

use Bff\Http\Url;
use Bff\Http\Method;
use Bff\Http\Request;
use Bff\Http\Response;
use Bff\Routing\Router;
use Bff\Routing\RouteHandler;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * @dataProvider methods
     *
     * @param string $method
     * @return void
     */
    public function testItCanMatchRequests(string $method) : void
    {
        $path = '/';
        $handler = function (Request $request, Response $response) : Response {
            return $response;
        };

        $router = new Router();
        $router->$method($path, $handler);

        $routeHandler = $router->match(Method::$method(), Url::from('http://example.com'));

        $this->assertSame(
            RouteHandler::class,
            get_class($routeHandler)
        );
    }

    /**
     * @dataProvider methods
     *
     * @param string $method
     * @return void
     */
    public function testItCanProvideDependenciesToResponseHandler(string $method) : void
    {
        $this->markTestIncomplete('Need the request class to be able to call `handle`');

        $path = '/';
        $handler = function (Request $request, Response $response) : Response {
            $this->assertSame(200, $response->statusCode());

            return $response->withStatusCode(400);
        };

        $router = new Router();
        $router->$method($path, $handler);

        $routeHandler = $router->match(Method::$method(), Url::from('http://example.com'));

        $routeHandler->handle(
            new Request()
        );
    }

    public function methods() : array
    {
        return [
            'GET' => ['get'],
            'POST' => ['post'],
            'PUT' => ['put'],
            'PATCH' => ['patch'],
            'DELETE' => ['delete'],
        ];
    }
}
