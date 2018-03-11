---
permalink: /docs/getting-started/configure-persistence/
title: Configure Persistence
---

# Configure Persistence

EventSauce has _two_ connections to persistence.

* The `MessageRepository` which contains `Message`s for reconstituting aggregates.
* The `MessageDispatcher` which is used to communicate `Message`s with `Consumer`s. 

Because of EventSauce's design it's possible to use traditional tools
to work like an event store. Databases like [Event Store](https://eventstore.org/)
implement both requires capabilities. In these cases You only need to use
the `MessageRepository` on side where we produce messages.

## Provided bindings:

Name | R | D
--- | --- | ---
[eventsauce/doctrine-message-repository](https://packagist.org/packages/eventsauce/doctrine-message-repository) | ✅ | ❌
[eventsauce/rabbitmq-bundle-bindings](https://packagist.org/packages/eventsauce/rabbitmq-bundle-bindings) | ❌ | ✅

R: Repository, D: Dispatcher