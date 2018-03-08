---
layout: default
permalink: /docs/event-sourcing/
title: Event Sourcing
---

# What is event sourcing?

Event sourcing is a way to model software where the emphasis is put
on why (and how) things change, rather than focusing on the current
state of the application. Action performed on the system are captured
in the form of events, which are communicated throughout the system.

Event sourcing is the answer to a very specific set of problems and
should not be applied carelessly. It's not a default strategy. However,
event sourcing is a very powerful way to model software and opens the
door to many new possibilities.

The cases where event sourcing shines are:

* Process modelling (finite flows, complex transitions).
* Cases where audit trails are of paramount importance.

