<?php declare(strict_types=1);

namespace Tolkam\CommandBus\Middleware\HandlerProvider\HandlerResolver;

use Psr\Container\ContainerInterface;
use RuntimeException;
use Tolkam\CommandBus\Middleware\HandlerProvider\HandlerResolverInterface;

class ContainerResolver implements HandlerResolverInterface
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;
    
    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * @inheritDoc
     */
    public function resolve($handler): ?callable
    {
        if (is_string($handler) && $this->container->has($handler)) {
            $resolved = $this->container->get($handler);
            
            if (!is_callable($resolved)) {
                throw new RuntimeException(sprintf(
                    'Handler %s is not callable',
                    $handler
                ));
            }
            
            return $resolved;
        }
        
        return null;
    }
}
