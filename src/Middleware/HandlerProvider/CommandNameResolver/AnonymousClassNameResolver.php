<?php declare(strict_types=1);

namespace Tolkam\CommandBus\Middleware\HandlerProvider\CommandNameResolver;

use Tolkam\CommandBus\Middleware\HandlerProvider\CommandNameResolverInterface;

/**
 * Resolves command name from anonymous class name
 *
 * @package Tolkam\CommandBus\Middleware\HandlerProvider\CommandNameResolver
 */
class AnonymousClassNameResolver implements CommandNameResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(object $command): string
    {
        return addslashes(get_class($command));
    }
}
