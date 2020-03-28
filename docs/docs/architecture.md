---
permalink: /docs/architecture/
redirect_from: '/docs/core-concepts/'
title: Architecture
published_at: 2018-03-24
updated_at: 2019-12-10
---

EventSauce is designed to be pragmatic. It's pragmatic because it's easy to setup
and easy to extend and replace. The library is focused on getting you up and
running quickly without sacrificing the conceptual integrity.

## Overview

The core of EventSauce revolves around a set of **3** interfaces:

> 1. `AggregateRootRepository`
> 2. `MessageRepository`
> 3. `MessageDispatcher`

The `AggregateRootRepository` is the main interface. It is responsible for
retrieving and persisting aggregate root objects. It uses the other two
parts for retrieving and storing `Message` objects (in which events are transported)
and dispatching messages to `MessageConsumer`s. Because EventSauce is based around these
interfaces it's very easy to modify how the library behaves. The interfaces also
make EventSauce highly customizable without the need for inheritance.

Which `MessageRepository` and/or `MessageDispatcher` you use is entirely up to you. There
are benefits (and downsides) to each dispatching mechanism and repository type. Because
EventSauce places these implementations behind an interface you're free to choose whatever
fits best. You can create your own repositories and dispatchers, the interfaces are
very tiny.

### Replaceable items

> * `AggregateRootRepository` - custom aggregate root construction and persistence
> * `MessageRepository` - custom message storage (database)
> * `MessageDispatcher` - customer message dispatching (queue)
> * `ClassNameInflector` - custom inflection for class-names to event names (interoperability)
> * `MessageSerializer` - custom message serialization (storage)
> * `PayloadSerializer` - custom event serialization (storage)

### Shipped Implementation

The default implementation of the `AggregateRootRepository` is the
`ConstructingAggregateRootRepository`. This repository requires your aggregate root
to implement the `AggregateRoot` interface. This implementation supports simple
aggregate root reconstitution and versioning for sequential integrity.

The library ships with an `InMemoryMessageRepository` (which can be used for testing)
and a `SynchronousMessageDispatcher`. Apart from that it ships with composition helpers
such as the `MessageDispatcherChain`. The dispatcher chain allows you to chain dispatchers,
this allows you to combine synchronous and asynchronous dispatching in the same composition.

```php
$dispatcher = new MessageDispatcherChain(
    new RabbitMQMessageDispatcher($producer, $serializer),
    new SynchronousMessageDispatcher($consumer, $anotherConsumer),
);
```

The message dispatcher chain will sub-dispatch the messages to the synchronous
dispatcher and the RabbitMQ dispatcher. 

## Core Concepts

Below are short descriptions of all parts that make up EventSauce.

### Aggregate Root

The aggregate root is your primary modeling space. It's tasked with maintaining the integrity
of the model, guarding invariants, and recording events.

### Aggregate Root Repository

The aggregate root repository is used to retrieve and "persist" aggregate root
entities. It's your main point of interaction when using the library. You
retrieve the aggregate root from it. After you've interacted with the aggregate
root this is also where you persist the newly raised events.

The aggregate root repository has the following dependencies:

1. An aggregate root class name (so it knows what to reconstitute and return)
2. A [message repository](#message-repository) from which it retrieves previously recorded events
3. A [message dispatcher](#message-dispatcher) which dispatches the messages _(optional)_
3. A [message decorator](#message-decorator) which decorated the messages _(optional)_

### Message Repository

The message repository is the library's connection to the persistence layer. It's responsible
for storing and retrieving events. Events stored in the repository are wrapped in a `Message`
object. This object allows you to store additional metadata alongside the event data.

### Message Dispatcher

The message dispatcher is responsible for sending messages to `MessageConsumer`s. The core
library ships with a `SynchronousMessageDispatcher` for when you don't need to process
messages asynchronously. If you do want to process events in the background there are
multiple options available.

### Message Decorator

A message decorator has the ability to enrich messages before they are persisted and dispatched.
This could be a request identifier so you can track an action from the web all the way down
to background processes. This mechanism prevents you from polluting the domain events with
specific information.

### Message Serializer

The message serializer is responsible for converting messages from and to a serialized form. When
using (or implementing) a message repository, you'll want to use this rely on this interface.

The core ships with a default (JSON based) serializer. You're free to implement your own
serialization strategy if your use-case requires it.

Message serializers can be composed using decoration to provide more complex features such as upcasting.

### Time

Time is a very important concept in EventSauce. In the core of the library a `Clock` is defined.
The `SystemClock` provides a production-ready implementation, while the `TestClock` is used during testing.

[Read more about the Clock](/docs/utilities/clock/)
