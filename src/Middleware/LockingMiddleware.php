<?php declare(strict_types=1);

namespace Tolkam\CommandBus\Middleware;

use Throwable;
use Tolkam\CommandBus\MiddlewareInterface;

/**
 * Queues incoming commands until the first one has completed
 *
 * @package Tolkam\CommandBus\Middleware
 */
class LockingMiddleware implements MiddlewareInterface
{
    /**
     * @var bool
     */
    private bool $isExecuting = false;
    
    /**
     * @var callable[]
     */
    private array $queue = [];
    
    /**
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute(object $command, callable $next)
    {
        $this->queue[] = fn() => $next($command);
        
        if ($this->isExecuting) {
            return null;
        }
        
        try {
            $this->isExecuting = true;
            
            return $this->executePending();
        } catch (Throwable $t) {
            $this->queue = [];
            throw $t;
        } finally {
            $this->isExecuting = false;
        }
    }
    
    /**
     * Executes any pending commands in the queue
     * and picks the first returned value
     *
     * @return mixed
     */
    protected function executePending()
    {
        $returnValues = [];
        while ($resumeCommand = array_shift($this->queue)) {
            $returnValues[] = $resumeCommand();
        }
        
        return array_shift($returnValues);
    }
}
