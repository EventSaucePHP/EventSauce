# EventSaucePHP

This is one way to do event-sourcing in PHP. It may not be the best, but it's mine.

It supports:

* Event sourcing.
* Easy testing.
* Serialization.
* Upcasting.
* Command/Event code generation.

That's it.

## Examples

Have an AggregateRoot

```php
<?php

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\Time\Clock;

final class EventSourcingThing extends AggregateRoot
{
    public function rename(string $newName, Clock $clock)
    {
        if ($this->name !== $newName) {
            $this->recordThat(new TheThingWasRenamed(
                $this->aggregateRootId(),
                $clock->pointInTime(),
                $newName
            ));
        }
    }
}
```

and test it.

```php
<?php

use EventSauce\EventSourcing\AggregateRootTestCase;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\CommandHandler;
use EventSauce\Time\Clock;

class EventSourcingThingTest extends AggregateRootTestCase
{
    protected function aggregateRootClassName(): string
    {
        return EventSourcingThing::class;
    }
    
    protected function commandHandler(AggregateRootRepository $repository, Clock $clock): CommandHandler
    {
        return new SomeCommandHandler($repository, $clock);
    }
    
    /**
     * @test
     */
    public function renaming()
    {
        $aggregateRootId = new AggregateRootId('identifier');
        $this->when(new RenameTheThing($aggregateRootId, 'new name'))
            ->then(new TheThingWasRenamed(
                $aggregateRootId,
                $this->pointInTime(),
                'new name'
            ));
    } 
}
```