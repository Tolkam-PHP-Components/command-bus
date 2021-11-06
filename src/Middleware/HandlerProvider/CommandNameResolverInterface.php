<?php declare(strict_types=1);

namespace Tolkam\CommandBus\Middleware\HandlerProvider;

interface CommandNameResolverInterface
{
    /**
     * @param object $command
     *
     * @return string
     */
    public function resolve(object $command): string;
}
