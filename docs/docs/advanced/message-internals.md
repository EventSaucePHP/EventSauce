---
permalink: /docs/advanced/message-internals/
title: Message Internals
published_at: 2018-03-11
updated_at: 2018-03-13
---

The `Message` object is the envelope in which events are stored and
dispatched to consumers. It contains an `Event` instance and headers.
The headers are meant for non-domain-event-specific information.
EventSauce uses a number of headers internally. All of the internal
headers are available as constants on the `EventSauce\EventSourcing\Header`
interface:

constant | value | description
--- | --- | ---
`Header::EVENT_ID` | `__event_id` | ID of the event (optional but recommended)
`Header::EVENT_TYPE` | `__event_type` | type of the event
`Header::TIME_OF_RECORDING` | `__time_of_recording` | when the event was recorded
`Header::AGGREGATE_ROOT_ID` | `__aggregate_root_id` | the aggregate root id
`Header::AGGREGATE_ROOT_ID_TYPE` | `__aggregate_root_id_type` | the type of aggregate root id
`Header::AGGREGATE_ROOT_VERSION` | `__aggregate_root_version` | the aggregate version (1-based sequence)

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
