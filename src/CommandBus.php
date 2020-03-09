<?php declare(strict_types=1);

namespace Tolkam\CommandBus;

class CommandBus implements CommandBusInterface
{
    /**
     * @var callable
     */
    private $next = null;
    
    /**
     * Build a callable chain from provided middlewares
     *
     * @param MiddlewareInterface[] $callableChain
     *
     * @return self
     */
    public function setMiddlewares(MiddlewareInterface ...$callableChain): self
    {
        $lastCallable = fn() => null;
        
        while ($middleware = array_pop($callableChain)) {
            $lastCallable = fn($command) => $middleware->execute($command, $lastCallable);
        }
        
        $this->next = $lastCallable;
        
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function handle(object $command)
    {
        if (!$this->next) {
            return null;
        }
        
        return call_user_func($this->next, $command);
    }
}
