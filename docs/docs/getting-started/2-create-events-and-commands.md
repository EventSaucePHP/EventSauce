---
permalink: /docs/getting-started/create-events-and-commands/
title: Create events and commands
published_at: 2018-03-11
updated_at: 2018-03-13
---

Events are the core of any event sourced system. They are the payload,
the message, they allow our system to communicate in a meaningful way.
Events and commands are very simple objects. They should be modelled
as "read-only" objects. This means they have to be instantiated with
all the data they need and _only_ expose that data. In EventSauce,
they have but one technical requirement:

> All events must be objects.

Depending on your serialization strategy your events may need to implement
more methods or indicate they implement a certain interface.

## Event serialization

In order to persist events they must be serializable. You can create your
own serialization strategy, or use the default ones provided.

By default the `MessageSerializer` uses the `EventSerializer` to serialize
events. This serializer requires events to implement the `SerializableEvent`
interface. This interface requires you to implement **2** public functions:

> 2. `toPayload(): array`
> 3. `fromPayload(array $payload): SerializableEvent`

## To and From payload

The `toPayload` and (static) `fromPayload` methods are used in the serialization
process. The `toPayload` method is expected to return an array that's serializable as JSON.
The `fromPayload` method is expected to create an instance from a deserialized JSON array.

To illustrate:

```php
$event1 = new MyEvent();
$event2 = MyEvent::fromPayload($event1->timeOfRecording());

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

use EventSauce\EventSourcing\Serialization\SerializableEvent;

class SomeEvent implements SerializableEvent
{
    public function toPayload(): array
    {
        return ['property' => $this->property];
    }

    public static function fromPayload(array $payload): SerializableEvent
    {
        return new SomeEvent($payload['property']);
    }
}
```

As you can see in the examples above, there are a handful of required methods.  The _from_ and _to_ payload methods are
used in the serialization process. This ensures the events can be properly stored. Values returned in the `toPayload`
method should be `json_encode`-able. Additional required properties for an event should be injected into the constructor
and properly formatted in the payload methods.

## Defining commands and events using YAML.

Commands and events aren't very special, they're often just glorified arrays with accessors. A common name for these kind
of objects is DTO (Data Transfer Object). Because of their simplicity it's possible to use code generation:

```php
<?php

use EventSauce\EventSourcing\CodeGeneration\CodeDumper;
use EventSauce\EventSourcing\CodeGeneration\YamlDefinitionLoader;

$loader = new YamlDefinitionLoader();
$dumper = new CodeDumper();
$phpCode = $dumper->dump($loader->load('path/to/definition.yml'));
file_put_contents($destination, $phpCode);
```

Here's an example YAML file containing some command and event definitions.

```yaml
namespace: Acme\BusinessProcess 
commands:
    SubscribeToMailingList:
        fields:
            username:
                type: string
                example: example-user
            mailingList:
                type: string
                example: list-name
    UnsubscribeFromMailingList:
        fields:
            username:
                type: string
                example: example-user
            mailingList:
                type: string
                example: list-name
            reason:
                type: string
                example: no-longer-interested
events:
    UserSubscribedToMailingList:
        fields:
            username:
                type: string
                example: example-user
            mailingList:
                type: string
                example: list-name
    UserUnsubscribedFromMailingList:
        fields:
            username:
                type: string
                example: example-user
            mailingList:
                type: string
                example: list-name
            reason:
                type: string
                example: no-longer-interested
```

Which compiles to the following PHP file:
 
 ```php
<?php

namespace Acme\BusinessProcess;

use EventSauce\EventSourcing\Serialization\SerializableEvent;

final class UserSubscribedToMailingList implements SerializableEvent
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    public function __construct(
        string $username,
        string $mailingList
    ) {
        $this->username = $username;
        $this->mailingList = $mailingList;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

    public static function fromPayload(array $payload): SerializableEvent
    {
        return new UserSubscribedFromMailingList(
            (string) $payload['username'],
            (string) $payload['mailingList']
        );
    }

    public function toPayload(): array
    {
        return [
            'username' => (string) $this->username,
            'mailingList' => (string) $this->mailingList
        ];
    }

    public function withUsername(string $username): UserSubscribedFromMailingList
    {
        $this->username = $username;
        
        return $this;
    }

    public function withMailingList(string $mailingList): UserSubscribedFromMailingList
    {
        $this->mailingList = $mailingList;
        
        return $this;
    }

    public static function with(): UserSubscribedFromMailingList
    {
        return new UserSubscribedFromMailingList(
            (string) 'example-user',
            (string) 'list-name'
        );
    }

}


final class SubscribeToMailingList
{ 
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    public function __construct(
        string $username,
        string $mailingList
    ) {
        $this->username = $username;
        $this->mailingList = $mailingList;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

}

final class UnsubscribeFromMailingList
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $mailingList;

    /**
     * @var string
     */
    private $reason;

    public function __construct(
        string $username,
        string $mailingList,
        string $reason
    ) {
        $this->username = $username;
        $this->mailingList = $mailingList;
        $this->reason = $reason;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function mailingList(): string
    {
        return $this->mailingList;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
 ```
