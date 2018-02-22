---
layout: default
permalink: /docs/
title: Event sourcing for PHP
---

# About EventSauce

EventSauce is an event sourcing library for PHP with a focus
on developer experience and productivity. It's a library, not
a framework.

The library is focused purely around event sourcing, **not** full-blown
CQRS/ES. If you need that I recommend checking out [Prooph](https://github.com/prooph)
or [Broadway](https://github.com/broadway/broadway). These libraries
have a higher level of entry but they do provide an "everything you
need is right here" experience.

## Motivation

EventSauce is a no-nonsense library for event-sourcing in PHP. This library
was developed with the idea that you should be able to add event sourced parts
to your application with easy. No application-wide rewrites, no big investements
upfront. 

The core is built around a set of (tiny) interfaces, this gives you the freedom
to choose the tools that meet your requirements.

EvenSauce puts the focus on event sourcing, not on things that happen around event
sourcing. It does not require you to follow CQRS patterns, although it does use
commands and command handlers. It does not require you to use a command-, event-,
or query-bus. By doing to it allows developers to use event sourcing for parts of
their application more easily.