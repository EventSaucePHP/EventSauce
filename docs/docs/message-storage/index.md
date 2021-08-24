---
permalink: /docs/message-storage/
title: About Message Storage
published_at: 2021-08-24
updated_at: 2021-08-24
---

Message repositories persist messages containing events for the event-sourced
aggregate roots. Configuring this piece of persistence is an essential part
of your setup and should be well understood.

EventSauce ships with a couple message repository implementations to get you started quickly:

- [Illuminate Message Repository](/docs/message-storage/illuminate/)
- [Doctrine 3 Message Repository](/docs/message-storage/doctrine-3/)
- [Doctrine 2 Message Repository](/docs/message-storage/doctrine-2/)

Each of the repository implementations support two table schemas. Read more about
the message storage [Table Schema](/docs/message-storage/repository-table-schema/).

The default implementations use UUIDs for identifying aggregates and events. You can
[customize UUID encoding](/docs/message-storage/uuid-encoding/) if needed.
