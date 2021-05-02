---
permalink: /docs/message-outbox/
title: Message Outbox
published_at: 2021-05-02
updated_at: 2021-05-02
---

Events recorded by an aggregate root are stored for reconstitution and dispatched
to notify interested consumers. It is common to use a queue for dispatch events.
Using a queue, the consumer can retry upon failure, which increases the fault
tolerance of a system. Unfortunately, there is also a downside to it.

When the aggregate root gets persisted, the recorded events are stored in a
database. After that, the events are sent to the queue. These are two separate
network interactions, which means one of them may fail. To ensure persisting
and dispatching events succeeds or fails as one operation, a message outbox
can be used.

A message outbox, [transactional outbox](https://microservices.io/patterns/data/transactional-outbox.html),
provides a solution for the double network interaction by buffering events in
the database used for reconstitution. The events are persisted in a separate table
in the database, used to re-dispatch them to a queue at a later time. Although
doing so adds latency to the overall event delivery pipeline, it ensures at least
once dispatching of the events. It also ensures events that are not persisted are
not communicated to consumers.

In this recorded live stream, Frank explains what problem the message outbox solved and
walks through implementing it.

<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/1Vjc4n9HtKM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


