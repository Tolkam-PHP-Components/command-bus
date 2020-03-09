# tolkam/command-bus

Simple command bus implementation with middleware support.

## Documentation

The code is rather self-explanatory and API is intended to be as simple as possible. Please, read the sources/Docblock if you have any questions. See [Usage](#usage) for quick start.

## Usage

````php
use Tolkam\CommandBus\CommandBus;
use Tolkam\CommandBus\Middleware\HandlerProvider\HandlerProviderMiddleware;
use Tolkam\CommandBus\Middleware\LockingMiddleware;

// example command
class MyCommand {
    protected $value;
    
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    
    public function getValue(): string {
        return $this->value;
    }
}

$commandBus = new CommandBus();

// configure command handlers
$handlerProviderMiddleware = new HandlerProviderMiddleware();
$handlerProviderMiddleware->setHandler(
    MyCommand::class,
    fn(MyCommand $myCommand)  => print('Got value: ' . $myCommand->getValue())
);

// add configured middlewares
$commandBus->setMiddlewares(
    $handlerProviderMiddleware,
);

$commandBus->handle(new MyCommand('my command value'));
````

## License

Proprietary / Unlicensed 🤷
