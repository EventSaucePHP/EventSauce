---
permalink: /docs/event-sourcing/configure-persistence/
redirect_from: /docs/getting-started/configure-persistence/
title: Configure Persistence
published_at: 2019-12-07
updated_at: 2021-08-24
---

EventSauce has _two_ connections to persistence.

* The `MessageRepository` which contains `Message`s for reconstituting aggregates.
* The `MessageDispatcher` which is used to communicate `Message`s with `MessageConsumer`s.

## `MessageRepository`

The message repository stores messages that aggregate roots use for reconstitution. The
repository has specific methods to query messages that belong to a single aggregate root.
A message repository should only be used for reconstitution. Performing arbitrary queries
on the underlying database is not advised, use a projection for this instead.

There are 3 message repository implementations shipped for v1:

- [Illuminate Message Repository](/docs/message-storage/illuminate/)
- [Doctrine 3 Message Repository](/docs/message-storage/doctrine-3/)
- [Doctrine 2 Message Repository](/docs/message-storage/doctrine-2/)

Each of the implementations support [two database table schemas](/docs/message-storage/repository-table-schema/).

### Learn more about message storage

View the [docs for message storage](/docs/message-storage/) to learn more.
