<?php declare(strict_types=1);

namespace Tolkam\CommandBus\Middleware\HandlerProvider;

use Tolkam\CommandBus\MiddlewareInterface;

class HandlerProviderMiddleware implements MiddlewareInterface
{
    /**
     * @var callable[]
     */
    private array $handlers = [];
    
    /**
     * @var array
     */
    private array $handlerResolvers = [];
    
    /**
     * @var CommandNameResolverInterface|null
     */
    private ?CommandNameResolverInterface $commandNameResolver;
    
    /**
     * @param HandlerResolverInterface|null     $defaultHandlerResolver
     * @param CommandNameResolverInterface|null $commandNameResolver
     */
    public function __construct(
        HandlerResolverInterface $defaultHandlerResolver = null,
        CommandNameResolverInterface $commandNameResolver = null
    ) {
        if ($defaultHandlerResolver) {
            $this->addResolver($defaultHandlerResolver);
        }
        
        $this->commandNameResolver = $commandNameResolver;
    }
    
    /**
     * @param HandlerResolverInterface $resolver
     *
     * @return self
     */
    public function addResolver(HandlerResolverInterface $resolver): self
    {
        $this->handlerResolvers[] = $resolver;
        
        return $this;
    }
    
    /**
     * @param string $commandName
     * @param        $handler
     *
     * @return self
     */
    public function setHandler(string $commandName, $handler): self
    {
        $this->handlers[$commandName] = $handler;
        
        return $this;
    }
    
    /**
     * @param array $handlers
     *
     * @return $this
     */
    public function setHandlers(array $handlers): self
    {
        foreach ($handlers as $commandName => $handler) {
            $this->setHandler($commandName, $handler);
        }
        
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function execute(object $command, callable $next)
    {
        return $this->getHandler($command)($command);
    }
    
    /**
     * @param object $command
     *
     * @return callable
     */
    private function getHandler(object $command): callable
    {
        $commandName = $this->commandNameResolver
            ? $this->commandNameResolver->resolve($command)
            : get_class($command);
        
        if (
            !isset($this->handlers[$commandName]) &&
            !array_key_exists($commandName, $this->handlers)
        ) {
            throw new HandlerProviderException(sprintf(
                'Handler for "%s" command is not set',
                $commandName
            ));
        }
        
        $handler = $this->handlers[$commandName];
        
        // handler is already callable
        if (is_callable($handler)) {
            return $handler;
        }
        
        foreach ($this->handlerResolvers as $resolver) {
            if ($handler = $resolver->resolve($handler)) {
                return $handler;
            }
        }
        
        throw new HandlerProviderException(sprintf(
            'Unable to resolve a callable from "%s" handler of "%s" command',
            $handler, $commandName
        ));
    }
}
