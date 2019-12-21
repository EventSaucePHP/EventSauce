---
permalink: /docs/utilities/event-dispatcher/
title: Event Dispatcher
published_at: 2019-12-09
updated_at: 2019-12-09
---

Adopting event sourcing is not always done by using it for new functionality.
In fact, you probably started out building an MVP in a CRUD-like way and now
you like to use event sourcing.

In these cases you'll need to be able to switch over gradually. To help with
this change, there's an `EventDispatcher`. The event dispatcher allows you
to emit events easily, from anywhere. This will allow you to emit events from
your current implementation, and switch to event sourcing when you've
introduced all necessary events.

The default implementation of the `EventDispatcher` interface is the
`MessageDispatchingEventDispatcher`. As the name suggests, this dispatcher
allows you to dispatch events. These events are wrapped in a message, decorated,
and dispatched to a `MessageDispatcher` of your choice.

### Example Usage:

```php
<?php

use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\MessageDispatchingEventDispatcher;

$eventDispatcher = new MessageDispatchingEventDispatcher(
    $yourMessageDispatcher,
    /* optional */ $yourMessageDecorator
);

$eventDispatcher->dispatch(
    new SomethingImportantHappened(),
    new SomeOtherThingHappened(),
);

$eventDispatcher->dispatchWithHeaders(
    [Header::AGGREGATE_ROOT_ID => $aggregateRootId->toString()],
    new SomethingImportantHappened(),
    new SomeOtherThingHappened(),
);
```
