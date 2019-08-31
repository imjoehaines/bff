<?php declare(strict_types=1);

namespace BffExample;

use Psr\Container\ContainerInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface;

final class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $dependencies = [];

    public function __construct()
    {
        $this->dependencies[IndexAction::class] = function (ContainerInterface $container) {
            return new IndexAction($container->get(ResponseFactoryInterface::class));
        };

        $this->dependencies[ResponseFactoryInterface::class] = function (ContainerInterface $container) {
            return new Psr17Factory();
        };
    }

    public function get($id)
    {
        return $this->dependencies[$id]($this);
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->dependencies);
    }
}
