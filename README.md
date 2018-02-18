# EventSaucePHP

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/EventSaucePHP/EventSauce/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/EventSaucePHP/EventSauce/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/EventSaucePHP/EventSauce/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/EventSaucePHP/EventSauce/?branch=master)
[![Build Status](https://travis-ci.org/EventSaucePHP/EventSauce.svg?branch=master)](https://travis-ci.org/EventSaucePHP/EventSauce)

EventSauce is a somewhat opinionated, no-nonsense, and easy way to introduce event sourcing into
PHP projects. It's designed so storage and queueing mechanisms can be chosen based on your
specific requirements. It has test tooling, designed to work with an event sourcing mindset.  

That's it.

## Examples

Have an AggregateRoot

```php
<?php

use EventSauce\EventSourcing\BaseAggregateRoot;
use EventSauce\EventSourcing\Time\Clock;

final class EventSourcingThing extends BaseAggregateRoot
{
    private $name;

    public function rename(Clock $clock, string $newName)
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
use EventSauce\EventSourcing\UuidAggregateRootId;
use EventSauce\EventSourcing\CommandHandler;
use EventSauce\EventSourcing\Time\Clock;

class EventSourcingThingTest extends AggregateRootTestCase
{
    protected function aggregateRootId(): AggregateRootId
    {
        return UuidAggregateRootId::create();
    }
    
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

### AggregateRootRepository

The `AggregateRootRepository` is our main point of infrastructural contact, 

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