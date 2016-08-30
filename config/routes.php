<?php

declare(strict_types=1);

use Bff\Router;
use Bff\Respond;
use Bff\Response;
use Psr\Http\Message\RequestInterface as Request;

return (new Router)
    ->get('/', function (Request $request, Respond $respond, int $a, int $b) : Response {
        return $respond(['a' => $a, 'b' => $b]);
    }, 1234, 5678)
    ->get('/thing', function (Request $request, Respond $respond) : Response {
        return $respond(['abc']);
    })
    ->get('/thing2', function (Request $request, Respond $respond) : Response {
        throw new Exception('Error');
    })
    ->post('/abc', function (Request $request, Respond $respond) : Response {
        return $respond($_POST);
    })
    ->get('/abc', function (Request $request, Respond $respond) : Response {
        return $respond(['get']);
    })
;
