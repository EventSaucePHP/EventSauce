---
permalink: /docs/getting-started/create-aggregate-root/
title: Create an Aggregate&nbsp;Root
---

Our first step is to create an event-sourced aggregate root. This is the domain model
we'll create that internally uses events. An aggregate root constructs itself from a
series of historical events. Inset of mutating properties directly, it uses events to
capture what is happening.

Simplified, you can say that a `historical events => model` and `model + action = new events`.

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
