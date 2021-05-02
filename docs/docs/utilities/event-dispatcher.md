---
permalink: /docs/utilities/event-dispatcher/
title: Event Dispatcher
published_at: 2019-12-09
updated_at: 2019-12-21
---

Events are a core concept of event sourcing, but they are useful
outside it as well. Dispatching events allow you to decouple systems.
Much of the tools they EventSauce is built on can be used to do just that.

When events are dispatched and stored in EventSauce, they are contained in
a `Message` object. This object contains the event and any additional
metadata. In order to simply the dispatching of events in a non-eventsourced
context, there is an `EventDispatcher`.

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
