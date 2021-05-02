---
permalink: /docs/event-sourcing/configure-persistence/
redirect_from: /docs/getting-started/configure-persistence/
title: Configure Persistence
published_at: 2019-12-07
updated_at: 2020-03-28
---

EventSauce has _two_ connections to persistence.

* The `MessageRepository` which contains `Message`s for reconstituting aggregates.
* The `MessageDispatcher` which is used to communicate `Message`s with `MessageConsumer`s.

Because of EventSauce's design it's possible to use traditional tools
to work like an event store. Databases like [Event Store](https://eventstore.org/)
implement both required capabilities. In these cases you only need to use
the `MessageRepository` on the side where we produce messages.

## Provided bindings:

Name | R | D
--- | --- | ---
[eventsauce/doctrine-message-repository](https://packagist.org/packages/eventsauce/doctrine-message-repository) | ✅ | ❌
[eventsauce/rabbitmq-bundle-bindings](https://packagist.org/packages/eventsauce/rabbitmq-bundle-bindings) | ❌ | ✅

R: Repository, D: Dispatcher
