---
permalink: /docs/advanced/message-internals/
title: Message Internals
---

The `Message` object is the envelope in which events are stored and
dispatched to consumers. It contains an event object and headers.
The headers are meant for non-domain-event-specific information.
EventSauce uses a number of headers internally. All of the internal
headers are available as constants on the `EventSauce\EventSourcing\Header`
interface:

| constant                         | value                      | description                                |
|----------------------------------|----------------------------|--------------------------------------------|
| `Header::EVENT_ID`               | `__event_id`               | ID of the event (optional but recommended) |
| `Header::EVENT_TYPE`             | `__event_type`             | type of the event                          |
| `Header::TIME_OF_RECORDING`      | `__time_of_recording`      | when the event was recorded                |
| `Header::AGGREGATE_ROOT_ID`      | `__aggregate_root_id`      | the aggregate root id                      |
| `Header::AGGREGATE_ROOT_ID_TYPE` | `__aggregate_root_id_type` | the type of aggregate root id              |
| `Header::AGGREGATE_ROOT_VERSION` | `__aggregate_root_version` | the aggregate version (1-based sequence)   |

## Message Decorator

If you want to add more headers you can use [Message Decorators](/docs/advanced/message-decoration/).

## Message Serialization

For persistence and dispatching, messages are serialized. The `MessageSerializer` interface
represents this boundary. By default the `ConstructingMessageSerializer` is used, which
serializes the event to the following format (shown JSON encoded):

```json
{
    "headers": {
        "__aggregate_root_id": "1234-1234-1234-1234"
    },
    "payload": {
        "key": "value"
    }
}
```

If needed you can create a custom implementation of the `MessageSerializer` to adapt to
existing serialization formats in your application or in systems you interact with.

### Class name inflection

By default, the `ContructionMessageSerializer` uses the `DotSeparatedSnakeCaseInflector` to 
convert event and id class names to a string when storing an event. When reconstructing the 
event from the repository it than used the class again to construct the get the FQN from the string.

For example:

`Domain\BankAccount\DomainEvents\TransactionRecorded::class` becomes `domain.bank_account.domain_events.transaction_recorded` in the database.


This couples the event's implementation details to the event storage, making it hard to refactor the Name or namespace 
of the event.

The `ExplicitlyMappedClassNameInflector` can be used to declare a map from event to string (added in 1.3.0).

```php
new ConstructingMessageSerializer(
    new \EventSauce\EventSourcing\ExplicitlyMappedClassNameInflector([
        // Map event types to a specified event type
        TransactionRecorded::class => 'transactions.transaction_recoded',
        
        // Use an array to map multiple event types to a class name.
        // The first entry is used to map the class to, the others are
        // Used to map previously emit event types to the specified class
        OrderHasShipped::class => ['orders.order_has_shipped', 'old.event_name'],
    ])
)
```

This method allows us to freely rename or move event classes.
A downside for this method, is that you need to be sure the class name is configured in lookup array.
