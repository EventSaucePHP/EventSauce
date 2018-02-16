# Rationale

## Motivation

EventSauce is a no-nonsense library for event-sourcing in PHP. This library
was developed with the idea that you should be able to add event sourced parts
to your application without application-wide rewrites. EventSauce will also try
to avoid coupling as much as possible. The core of EventSauce is built around
a set of interfaces, this makes it easy to integrate with tools you're already using. 

EvenSauce puts the focus on event sourcing, not on things that happen around event
sourcing. It does not require you to follow CQRS patterns, it does not require you
to use a command-, event-, or query-bus. By doing to it allows developers to use
event sourcing for parts of their application more easily. 

## The parts 

There are several parts that make up an event sourcing library:

### Aggregate Root

The aggregate root is our primary modeling space.

### Aggregate Root Repository

The aggregate root repository is used to retrieve and "persist" aggregate root
entities. It's your main point of interaction when using the library. You
retrieve the aggregate root from it. After you've interacted with the aggregate
root this is also the place where you persist the newly raised events.

The aggregate root consists of two parts:

1. An aggregate root class name (so it knows what to reconstitute and return)
2. A [message repository](#message-repository) (from which it retrieves the actual events)
3. A [message decorator](#message-decorator) (*optional* which decorates _messages_)

### Message Repository

The message repository is the library's connection to the persistence layer. It's responsible
for storing and retrieving events. Events stored in the repository are wrapped in a `Message`
object. This object allows you to store additional meta-data alongside the event data.

### Message Decorator

A message decorator has the ability to enrich messages with extra information. This could be
a request identifier so you can track an action from the web all the way down to background
processes. This mechanism prevents you from polluting the domain events with non-domain
specific information.

### Message Serializer(s)

Message serializers are responsible for converting messages from and to a serialized form. When
using (or implementing) a message repository, you'll want to use this rely on this interface.

Message serializers can be composed using decoration to provide more complex features such as upcasting.

### Time

Time is a very important concept in EventSauce. In the core of the library a `Clock` is defined.
The `SystemClock` provides a production-ready implementation, while the `TestClock` is used during testing.
The `TestClock` allows you to fixate time, allowing you to test processes over time. 

