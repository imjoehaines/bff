<?php

require_once __DIR__ . '/../vendor/autoload.php';

$router = require __DIR__ . '/../config/routes.php';

$request = new GuzzleHttp\Psr7\Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

Bff\App::run($router, $request);
