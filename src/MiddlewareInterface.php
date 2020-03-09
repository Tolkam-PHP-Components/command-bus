<?php declare(strict_types=1);

namespace Tolkam\CommandBus;

interface MiddlewareInterface
{
    /**
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute(object $command, callable $next);
}
