---
permalink: /docs/event-sourcing/
title: Event Sourcing
alternate_title: What is event sourcing?
published_at: 2018-03-08
updated_at: 2019-12-21
---
Event sourcing is a radically different way to model software, compared to
traditional entity/state based modeling. It puts an emphasis in change,
rather than focusing on the current state of the application. With event
sourcing, events are the fundamental building blocks in our software. These
events are used to build the decision-models and the presentational models.
They allow the core of your software to remain blissfully unaware of the
outside world.

## When event sourcing shines.

This style of programming especially fit for:

* Business process modeling (finite flows, complex transitions and transactions).
* Complex user flows.
* Asynchronous system interactions
* Cases where audit trails are of paramount importance.

## How is it different?

Regular OOP-style, entity based modeling the software model is tightly
coupled with the data model. Our code is a reflection this data-centric
view. When we model our domain in this fashion we often start out by
modeling the "things". The actions performed on our model changes its
state, which is then persisted to become the new state of the model.
When we test our code, we assert based on _current_ application _state_. 

> Over time state is modified/created/removed in order to keep up with changes.

In event sourcing this concept is turned upside-down. The model is constructed 
using events that describe things that have happened in the past. In order to
get to the current state, we replay all the events needed to make the next move.
These events give our model the information to base new decisions on. Actions
dispatched to the model result in any number of new events. These events are
used for our next decision, and to communicate change throughout the system.

## Event and Message Driven Programming

Event sourcing is a style of programming that builds off event- and message-driven
programming. In this style of programming the focus is put on communication. Since
we're primarily modeling using messages, communicating change is very easy to facilitate.

Every event is a representation of something that happened. This is a very powerful
thing. Apart from data it transmits intent. Where state based models make it difficult
to infer intent, with event sourcing you won't even have to infer it in the first place.
It'll just be clear.

## The cost and trade-offs

Coming from data-centric modeling, event sourcing is a pretty big paradigm shifts.
It is also not a cost-free solution. Like many things in our work-field, by introducing
event sourcing into your application you're trading one set of problems for another.
You're opting in to certain things, and out of others.

Querying the current state of the application must now be facilitated (if you need it).
The upside to this is that you can now build a number of different ways to view your
data from the events.

In general, event sourcing is a lot more verbose. You'll often have to introduce
some additional pieces of infrastructural. It's possible to minimize this initial
investment, but like any trade-off, this comes as a cost.

## The pay-off

Event sourcing opens up an exciting amount of new possibilities. There's also a number
of theoretical and practical benefits that are possible when using event sourcing.

Scaling an application, both on the _read_ and _write_ side, is much easier. On the read
side we can use projections to create a multitude of data presentations (read models).
On the write size we can use sharding to distribute the load to a (growing) number of
workers.

Because all events that belong to the same process have the same identifier, it's also easier
to know when handling of events can be done in parallel.

In general, it's easier to respond to change due to the message-based nature. Change is at
the heart of an event sourced application. Everything you do is acting, causing events, and
reacting. Because of this, background processing can be done more frequently, and with ease.
Moving processes from the main request to the background is also a lot easier.
