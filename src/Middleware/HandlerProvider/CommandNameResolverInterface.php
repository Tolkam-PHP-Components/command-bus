<?php declare(strict_types=1);

namespace Tolkam\CommandBus\Middleware\HandlerProvider;

interface CommandNameResolverInterface
{
    /**
     * @param object $command
     *
     * @return callable|null
     */
    public function resolve(object $command): string;
}
