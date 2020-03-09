<?php declare(strict_types=1);

namespace Tolkam\CommandBus\Middleware\HandlerProvider;

interface HandlerResolverInterface
{
    /**
     * @param $handler
     *
     * @return callable|null
     */
    public function resolve($handler): ?callable;
}
