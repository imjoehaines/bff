<?php

declare(strict_types=1);

namespace BffTests\Routing;

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

        $routeHandler = $router->match(Method::$method(), '/');

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
    public function testItWill404WhenRequestHasNoMatchingRoute(string $method) : void
    {
        $handler = function (Request $request, Response $response) : Response {
            return $response;
        };

        $router = new Router();

        $routeHandler = $router->match(Method::$method(), '/');

        $response = $routeHandler->handle(
            new Request(Method::$method(), new Parameters())
        );

        $this->assertSame(404, $response->statusCode());
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

        $routeHandler = $router->match($method, $path);

        $response = $routeHandler->handle(
            new Request($method, new Parameters())
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

        $routeHandler = $router->match($method, $path);

        $response = $routeHandler->handle(
            new Request($method, new Parameters())
        );

        $this->assertSame(400, $response->statusCode());
    }

    /**
     * @dataProvider methods
     *
     * @param string $method
     * @return void
     */
    public function testItRoutesRequestsWithVariable(string $method) : void
    {
        $handler = function (Request $request, Response $response, int $id) : Response {
            $this->assertSame(999, $id);

            $this->assertSame(200, $response->statusCode());

            return $response->withStatusCode(400);
        };

        $router = new Router();
        $router->$method('/\/([0-9]{3})/', $handler);

        $method = Method::$method();

        $routeHandler = $router->match($method, '/999');

        $response = $routeHandler->handle(
            new Request($method, new Parameters())
        );

        $this->assertSame(400, $response->statusCode());

        // check that 2 numbers doesn't match the route
        $routeHandler = $router->match($method, '/99');

        $response = $routeHandler->handle(
            new Request($method, new Parameters())
        );

        $this->assertSame(404, $response->statusCode());
    }

    /**
     * @dataProvider methods
     *
     * @param string $method
     * @return void
     */
    public function testItHandlesFloatVariables(string $method) : void
    {
        $handler = function (Request $request, Response $response, float $id) : Response {
            $this->assertSame(9.9, $id);

            $this->assertSame(200, $response->statusCode());

            return $response->withStatusCode(400);
        };

        $router = new Router();
        $router->$method('/\/([0-9].[0-9])/', $handler);

        $method = Method::$method();

        $routeHandler = $router->match($method, '/9.9');

        $response = $routeHandler->handle(
            new Request($method, new Parameters())
        );

        $this->assertSame(400, $response->statusCode());
    }

    /**
     * @dataProvider methods
     *
     * @param string $method
     * @return void
     */
    public function testItRoutesRequestsWithMultipleVariables(string $method) : void
    {
        $handler = function (Request $request, Response $response, int $id, string $greeting) : Response {
            $this->assertSame(2, $id);
            $this->assertSame('hello', $greeting);

            $this->assertSame(200, $response->statusCode());

            return $response->withStatusCode(400);
        };

        $router = new Router();
        $router->$method('/\/([0-9])\/([a-z]+)/', $handler);

        $method = Method::$method();

        $routeHandler = $router->match($method, '/2/hello');

        $response = $routeHandler->handle(
            new Request($method, new Parameters())
        );

        $this->assertSame(400, $response->statusCode());
    }

    /**
     * @dataProvider methods
     *
     * @param string $method
     * @return void
     */
    public function testItRoutesRequestsWithMultipleVariablesAndDependencies(string $method) : void
    {
        $handler = function (
            Request $request,
            Response $response,
            int $id,
            string $greeting,
            array $data
        ) : Response {
            $this->assertSame(2, $id);
            $this->assertSame('hello', $greeting);
            $this->assertSame([1, 2, 3, 4, 5], $data);

            $this->assertSame(200, $response->statusCode());

            return $response->withStatusCode(400);
        };

        $router = new Router();
        $router->$method('/\/([0-9])\/([a-z]+)/', $handler, [[1, 2, 3, 4, 5]]);

        $method = Method::$method();

        $routeHandler = $router->match($method, '/2/hello');

        $response = $routeHandler->handle(
            new Request($method, new Parameters())
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
