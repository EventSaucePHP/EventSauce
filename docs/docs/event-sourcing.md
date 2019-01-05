---
permalink: /docs/event-sourcing/
title: Event Sourcing
alternate_title: What is event sourcing?
published_at: 2018-03-08
updated_at: 2019-01-05
---

Event sourcing is a way to model software where the emphasis is put
on why (and how) things change, rather than focusing on the current
state of the application. Actions performed on the system result in
events, which are communicated throughout the system.

## When event sourcing shines.

This style of programming especially fit for:

* Process modelling (finite flows, complex transitions and transactions).
* Cases where audit trails are of paramount importance.

## How is it different?

Regular OOP-style modelling is closely modelled towards the data model.
Our code reflects this data-centric view. When we model our domain we
often start out by modelling the "things". Actions performed on our model
results in new state, which is then persisted to become the new state.
When we test our code, we assert based on _current_ application _state_. 

> Over time state is modified/created/removed in order to keep up with changes.

In event sourcing this concept is turned upside-down. The model is constructed 
using events that describe things that have happened in the past. In order to
get to the current state, we need to replay all the events associated with a
process or entity. These events give our model the information to base new
decisions on. Actions dispatched to the model result in any number of new
events raised. These events are used to communicate change throughout the system.

## Event and Message Driven Programming

Event sourcing is a style of programming that builds off event- and rmessage-driven
programming. In this style of programming the focus is put on communication. Because
we're primarily modelling using messages communicating change is very easy to facilitate.

## The cost and trade-offs

Coming from data-centric modelling, event sourcing is a pretty big paradigm shifts.
It is also not a cost-free solution. Like many things in our work-field, by introducing
event sourcing into your application you're trading one set of problems with another.
You're also implicitly opting out of some things. Querying the current state of the
application must now be facilitated. In general event sourcing is a lot more verbose.
You'll also probably have to introduce a little more infrastructure to get up
and running. In event sourcing you can minimize that initial investment but everything
is a trade-off.

## The opportunities

Event sourcing opens up an exciting amount of new possibilities. It's easier
to respond to change due to the message-based nature. You can create data-views
specialized for certain cases, these are called "read models". Background
processing can be done more frequently if needed. Moving processes from the main
request to the background is also a lot easier.
