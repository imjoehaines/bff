<?php

declare(strict_types=1);

namespace Bff;

use Psr\Http\Message\RequestInterface as Request;

class App
{
    public static function run(Router $router, Request $request)
    {
        $routeHandler = $router->match($request);

        $response = $routeHandler->handle($request);

        http_response_code($response->getStatusCode());
        header('Content-type: application/json');

        echo $response;
    }
}
