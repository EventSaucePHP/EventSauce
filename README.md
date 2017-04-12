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
    
    protected function applyTheThingWasRenamed(TheThingWasRenamed $event)
    {
        $this->name = $event->name();
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

## Concepts

### AggregateRoot

The `AggregateRoot` is the main `Entity` which is our contact point to the internal behavior/process.

### Event

An event represents something that happened which is relevant to the business.

### Message

A message is the distributed format of an event which can contain relevant, not domain specific, metadata.

### MessageRepository

The message is responsible for persisting and retrieving `Message`'s. A `MessageRepository` is like an EventStore, but
contains messages instead of purely events.

### MessageDispatcher

The `MessageDispatcher` is responsible for dispatching messages to `Consumer`'s.

### Consumer

A `Consumer` handles `Messages`'s.

### ProcessManager?

This is just a `Consumer`.

### Projector?

This is just a `Consumer`. 