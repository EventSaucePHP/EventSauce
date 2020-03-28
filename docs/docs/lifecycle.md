---
permalink: /docs/lifecycle/
title: Lifecycle
published_at: 2018-02-25
updated_at: 2019-11-09
---

The lifecycle of EventSauce can be broken down into **3** steps:

1. [Interacting with the model](#interacting-with-the-model).
2. [Storing and dispatching raised events](#storing-and-dispatching-raised-events).
3. [Reacting to dispatched events](#reacting-to-dispatched-events).

## Interacting with the model

In order to interact with your model  you'll need to
retrieve the `AggregateRoot` from the `AggregateRootRepository`.

### Retrieving the `AggregateRoot`

```php
$aggregateRoot = $repository->retrieve($aggregateRootId);
```

Retrieving an aggregate root fetches all the events that belong to
your aggregate root (related by aggregate root ID). You can reconstruct
an instance of your aggregate root by calling its `::reconstituteFromEvents`
method and passing the events to it.

The reconstitution process applies all the previously
recorded events on the instance. This brings our model to the current
state, ready for us to interact with it.

### Performing actions

Now you that you've got the model, you can perform actions on it.
It is recommended to encapsulate this interaction in a **command
handler** or **service layer**.

```php
// Send a command to the aggregate root directly
$aggregateRoot->performAction($command);

// Or pass individual parameters
$aggregateRoot->performAnotherAction(
    $command->parameter(),
    $command->otherParameter()
);
```

In the aggregate you can now guard invariants and record events:

```php
// Inside our aggregate root class
public function performAction(SomeCommand $command)
{
    $this->guardBusinessRule($command);
    $this->recordThat(new SomeActionWasPerformed(
        $command->parameter(),
        $command->otherParameter()
    ));
}
```

Or if you used the parameterised approach:

```php
// Inside our aggregate root class
public function performAnotherAction(
    int $param1,
    string $param2
) {
    $this->guardBusinessRule($param1, $param2);
    $this->recordThat(new SomeActionWasPerformed(
        $param1,
        $param2
    ));
}
```

Whenever you record an event the `recordThat` ensures it's immediately
applied. This ensures the aggregate root is ready for the next interaction
without needing to re-retrieve it from the aggregate root repository.

```php
// Inside our aggregate root class
public function applySomeActionWasPerformed(SomeActionWasPerformed $event)
{
    // Use the data from the event to bring the current state up to date.
}
```

> It's important to note that applying events must **never** have side-effects.
> The only job of this function is to *use* the data from the event. Applying 
> an event must not cause any exceptions.


## Storing and dispatching raised events

Once you're done interacting with the aggregate root you'll need to persist
the newly generated events.

```php
$repository->persist($aggregateRoot);
```

When you persist the events from an aggregate root the following things happen:

1. Events are pulled from the aggregate root.
2. The events are wrapped in a `Message` objects.
3. The message objects are decorated (optional).
4. The messages are persisted in the `MessageRepository`.
5. The messages are dispatched by the `MessageDispatcher`.

## Reacting to dispatched events

Responding to messages is the final step of the event sourcing lifecycle.
The `MessageDispatcher` is responsible for passing `Message`s on to
`MessageConsumer`s.

Typical consumer types are:

* Projections: processes events to update "read models".
* Process Managers: listen to events and then perform actions.

```php
use EventSauce\EventSourcing\MessageConsumer;

class SomeConsumer implements MessageConsumer
{
    public function handle(Message $message)
    {
        $aggregateRootId = $message->aggregateRootId();
        $event = $message->event();
        $allHeaders = $message->headers();
        $requestId = $message->header('x-request-id');
    }
}
```
