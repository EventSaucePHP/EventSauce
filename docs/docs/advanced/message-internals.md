---
layout: default
permalink: /docs/advanced/message-internals/
title: Message Internals
---

# Message Internals

The `Message` object is the envelope in which events are stored and
dispatched to consumers. It contains a `Event` instance and headers.
The headers are meant for non-domain-event specific information.
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

## Message Decorator

You you want to add more headers you can use [Message Decorators](/docs/advanced/message-decoration/).

## Message Serialization

For persistence and dispatching message are serialized. The `MessageSerializer` interface
represents this boundary. By default the `ConstructingMessageSerializer` is used. Which
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