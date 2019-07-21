---
permalink: /docs/upgrading/to-0-6-0
title: Upgrade to 0.6.0
published_at: 2019-07-20
updated_at: 2019-07-20
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
