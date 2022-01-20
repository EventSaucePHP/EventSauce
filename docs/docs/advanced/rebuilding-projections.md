---
permalink: /docs/advanced/rebuilding-projections/
title: Rebuilding Projections
published_at: 2018-03-13
updated_at: 2018-03-23
---

Rebuilding projections is one of the more complicated subjects in
event sourcing. EventSauce takes an uncommon approach to tackle this
problem: it **does not** tackle the problem.

Generic rebuild tooling is very complex and imposes some additional
constraints. Even then generic tooling will probably cover about 80%
of the use-cases.

The way that EventSauce is designed allows for very easy extension.
Implementing your own `MessageRepository` and/or `MessageDispatcher` is
done in a matter of minutes. Keeping this interface simple was a very
important decision. It means you're able to take **full control** of it
when (and&nbsp;if) needed.

## Naive replay example

`eventsauce/replay-consumer` is the most naive implementation of a rebuild class. 
This can be used as inspiration or a starting point for your own implementation that fits your use-case.

The replay consists of 2 steps: 
1. Drop the current state of your projections. 
2. Fetch events from the repository, and apply them to the consumers

Some **important** considerations when using the naive way of rebuilding state:

1. During a rebuild, your projection will display old, incorrect data. 
Your projections will be correct again after the rebuild has finished. 
2. Recording new events during the rebuild will result in:
   1. The new event being handled twice
   2. The events being handled in an incorrect order

Because of the above points, it's recommended to prevent users from using your application during a rebuild.

There are methods to rebuild a projection while keeping the application up. 

A common pattern is to create a rebuild in the background, to a copy of your live projection. 
When all events are applied to the rebuild, hot-swap the new projection for the old one. 

Another pattern is to introduce some logic in the Consumers. 
For example, a handler could keep track of the last event it applied for a specific aggregate ID. 
When it receives an event for the aggregate, it fetches all missing events from the MessageRepository, and applies them in the right order.
Triggering a replay for a certain aggregate can now be done by calling a specific replay method on the consumer.
