---
permalink: /docs/event-sourcing/create-events-and-commands/
redirect_from: /docs/getting-started/create-events-and-commands/
title: Create events and commands
published_at: 2019-12-07
updated_at: 2019-12-21
---

Events are the core of any event sourced system. They are the payload,
the message, they allow our system to communicate in a meaningful way.
Events and commands are very simple objects. They should be modeled
as "read-only" objects. This means they have to be instantiated with
all the data they need and _only_ expose that data. In EventSauce,
they have but one technical requirement:

> All events must be objects.

Depending on your serialization strategy your events may need to implement
more methods or indicate they implement a certain interface.

## Event serialization

In order to persist events they must be serializable. You can create your
own serialization strategy, or use the default ones provided.

By default the `MessageSerializer` uses the `PayloadSerializer` to serialize
events. This serializer requires events to implement the `SerializablePayload`
interface. This interface requires you to implement **2** public functions:

> 2. `toPayload(): array`
> 3. `fromPayload(array $payload): SerializablePayload`

## To and From payload

The `toPayload` and (static) `fromPayload` methods are used in the serialization
process. The `toPayload` method is expected to return an array that's serializable as JSON.
The `fromPayload` method is expected to create an instance from a deserialized JSON array.

To illustrate:

```php
$event1 = new MyEvent();
$event2 = MyEvent::fromPayload($event1->toPayload());

assert($event1 == $event2);
```

## Defining events (and commands)

Defining events and commands can be done in 2 ways.

* Defining them in YAML (code generation).
* Creating classes by pressing keys on your keyboard.


## Manually creating classes.

EventSauce provides interfaces for events and commands. You can create implementations of this. Here are minimal
examples.

### Event

```php
<?php

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class SomeEvent implements SerializablePayload
{
    public function toPayload(): array
    {
        return ['property' => $this->property];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new SomeEvent($payload['property']);
    }
}
```

As you can see in the examples above, there are just 2 required methods.  The _from_ and _to_ payload methods are
used in the serialization process. This ensures the events can be properly stored. Values returned in the `toPayload`
method should be `json_encode`-able. Additional required properties for an event should be injected into the constructor
and properly formatted in the payload methods.

## Defining commands and events using YAML.

Find out [how to define commands and events using YAML](/docs/code-generation/from-yaml/)
