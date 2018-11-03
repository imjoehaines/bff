<?php

declare(strict_types=1);

namespace BffTests\Routing;

use Bff\Http\Url;
use Bff\Http\Method;
use Bff\Http\Request;
use Bff\Http\Response;
use Bff\Routing\Router;
use Bff\Http\Parameters;
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
    public function testItRoutesRequests(string $method) : void
    {
        $path = '/';
        $handler = function (Request $request, Response $response) : Response {
            $this->assertSame(200, $response->statusCode());

            return $response->withStatusCode(400);
        };

        $router = new Router();
        $router->$method($path, $handler);

        $method = Method::$method();
        $url = Url::from('http://example.com');

        $routeHandler = $router->match($method, $url);

        $response = $routeHandler->handle(
            new Request($method, $url, new Parameters())
        );

        $this->assertSame(400, $response->statusCode());
    }

    /**
     * @dataProvider methods
     *
     * @param string $method
     * @return void
     */
    public function testItCanProvideDependenciesToRouteHandlers(string $method) : void
    {
        $path = '/';
        $handler = function (
            Request $request,
            Response $response,
            int $number,
            string $string,
            array $array
        ) : Response {
            $this->assertSame(200, $response->statusCode());

            $this->assertSame(1, $number);
            $this->assertSame('hi', $string);
            $this->assertSame([1, 2, 3], $array);

            return $response->withStatusCode(400);
        };

        $router = new Router();
        $router->$method($path, $handler, [1, 'hi', [1, 2, 3]]);

        $method = Method::$method();
        $url = Url::from('http://example.com');

        $routeHandler = $router->match($method, $url);

        $response = $routeHandler->handle(
            new Request($method, $url, new Parameters())
        );

        $this->assertSame(400, $response->statusCode());
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
