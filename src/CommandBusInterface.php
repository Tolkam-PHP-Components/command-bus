<?php declare(strict_types=1);

namespace Tolkam\CommandBus;

interface CommandBusInterface
{
    /**
     * Handles a command
     *
     * @param object $command
     *
     * @return mixed
     */
    public function handle(object $command);
}
