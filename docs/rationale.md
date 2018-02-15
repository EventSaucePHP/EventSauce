# Rationale

EventSauce is a no-nonsense library for event-sourcing in PHP. This library
was developed with the idea that you should be able to add event sourced parts
to your application without application-wide rewrites. EventSauce will also try
to avoid coupling as much as it can. By having well defined interfaces you can
implement you'll be less likely to be locked into the library.

## The parts

There are several parts that make up an event sourcing library:

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
