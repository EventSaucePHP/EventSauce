---
permalink: /docs/upgrading/to-0-6-0
title: Upgrade to 0.6.0
published_at: 2019-07-21
updated_at: 2019-12-21
---

Event serialization is made generic for any payload so commands can leverage the
same serialization. It's not called "payload serialization".

```diff
-use EventSauce\EventSourcing\Serialization\EventSerializer;
+use EventSauce\EventSourcing\Serialization\PayloadSerializer;

-use EventSauce\EventSourcing\Serialization\SerializableEvent;
+use EventSauce\EventSourcing\Serialization\SerializablePayload;

-$serializer = new EventSauce\EventSourcing\Serialization\ConstructingEventSerializer();
+$serializer = new EventSauce\EventSourcing\Serialization\ConstructingPayloadSerializer();
```

## Aggregate Root Construction

The `AggregateRootBehaviour` trait includes a construction, this constructor
is now private by default. It's recommended to use named constructors. For more
information, view the [aggregate root construction documentation](https://eventsauce.io/docs/getting-started/create-an-aggregate-root/#aggregate-construction).
