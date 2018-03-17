---
permalink: /docs/event-sourcing/
title: Event Sourcing
alternate_title: What is event sourcing?
published_at: 2018-03-11
updated_at: 2018-03-12
---

Event sourcing is a way to model software where the emphasis is put
on why (and how) things change, rather than focusing on the current
state of the application. Action performed on the system are captured
in the form of events, which are communicated throughout the system.

Event sourcing is the answer to a very specific set of problems and
should not be applied carelessly. It's not a default strategy. However,
event sourcing is a very powerful way to model software and opens the
door to many new possibilities.

## When event sourcing shines.

This style of programming especially fit for:

* Process modelling (finite flows, complex transitions).
* Cases where audit trails are of paramount importance.

## How is it different?

Regular OOP-style modelling is closely modelled towards the data model.
Our code reflects this data-centric view. When we test our code, we
assert based on current application state. When we create our code
we often start out by modelling the "things" not the behaviour.

> Over time state is modified/created/removed in order to keep up with changes.

In event sourcing this concept is turned upside-down. Software is modelled 
using events that describe things when they happen. In order to get to the
current state, all we need to do is replay all the events associated
with a given scope/process/entity. Event sourcing is a style of programming
that builds off event-driven (or message-driven) programming. In this
style of programming the focus is put on communication. Because we're
primarily modelling using messages communicating change is very easy to
facilitate.

## The cost of event sourcing

Event sourcing is one of the bigger paradigm shifts in software modelling.
It is also not a cost-free solution. Like many things in our work-field,
by introducing event sourcing into your application you're effectively
trading one set of problems with another. You're also implicitly opting
out of some things. Querying the current state of the application must now
be facilitated. In general event sourcing is a lot more verbose. You'll
also probably have to introduce a little more infrastructure to get up
and running.

## The opportunities

Event sourcing opens up an exciting amount of new possibilities. It's easier
to respond to change due to the message-based nature. You can create data-views
specialized for certain cases, mostly referred to as "read models". Background
processing can be done more frequently if needed. Moving processes from the main
request to the background is also a lot easier.
