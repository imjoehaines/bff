<?php

declare(strict_types=1);

namespace Bff;

class App
{
    public static function run(Router $router, Request $request)
    {
        $routeHandler = $router->match($request->getMethod(), $request->getPath());

        $response = $routeHandler->handle($request);

        http_response_code($response->getStatusCode());
        header('Content-type: application/json');

        echo $response;
    }
}
