---
layout: default
permalink: /docs/core-concepts/
title: Core Concepts
---

# Core Concepts

## The Architecture

EventSauce consists of 3 parts; a **core** library, and two parts that you can choose
based on your requirements. The `MessageRepository`, which is used
to persist and retrieve events, and a `MessageDispatcher`, which allows you to broadcast
events to `Consumer`s. The core library ships with a `SynchronousMessageDispatcher`,
so having a queueing system is not an immediate requirement.

Which `MessageRepository` or `MessageDispatcher` you use it totally up to you. There
are benefits (and downsides) to each queueing mechanism and message repository. Because
EventSauce places these implementations behind an interface you're free to choose whatever
fits best. You can even create your own repositories and dispatchers, the interface is
very tiny.

### Aggregate Root

The aggregate root is our primary modeling space. It's tasked with maintaining the integrity
of our model, guarding invariants, and recording events.

### Aggregate Root Repository

The aggregate root repository is used to retrieve and "persist" aggregate root
entities. It's your main point of interaction when using the library. You
retrieve the aggregate root from it. After you've interacted with the aggregate
root this is also where you persist the newly raised events.

The aggregate root has the following dependencies:

1. An aggregate root class name (so it knows what to reconstitute and return)
2. A [message repository](#message-repository) from which it retrieves previously recorded events
3. A [message dispatcher](#message-dispatcher) which dispatches the messages _(optional)_
3. A [message decorator](#message-decorator) which decorated the messages _(optional)_

### Message Repository

The message repository is the library's connection to the persistence layer. It's responsible
for storing and retrieving events. Events stored in the repository are wrapped in a `Message`
object. This object allows you to store additional metadata alongside the event data.

### Message Dispatcher

The message dispatcher is responsible for sending messages to `Consumer`s. The core
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
The `TestClock` allows you to fixate time, allowing you to test processes without worrying about the current
time changing under you. 

