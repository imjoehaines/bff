<?php declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Bff\App;
use Bff\Router;
use Bff\Emitter;
use BffExample\Container;
use BffExample\IndexAction;

$container = new Container();

$router = new Router($container);
$router->get('/', IndexAction::class);

$app = new App($router);
$response = $app->run($_SERVER);

$emitter = new Emitter();
$emitter->emit($response);
