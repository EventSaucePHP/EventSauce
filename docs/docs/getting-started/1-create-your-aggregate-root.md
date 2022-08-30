---
permalink: /docs/getting-started/create-aggregate-root/
title: Create an Aggregate&nbsp;Root
---

Our first step is to create an event-sourced aggregate root. This is the software model
we'll create that internally uses events. The aggregate root has functionality to rebuild
itself from stored events. It also has functionality to record and expose new events.

EventSauce represents an aggregate root internally as an interface. Your model will be an
implementation of that interface. To make it easy, a default implementation is supplied in
the form of a trait, preventing you from having to write some boilerplate code.

```php
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class YourAggregateRoot implements AggregateRoot
{
    use AggregateRootBehaviour;
}
```
