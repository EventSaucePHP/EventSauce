---
layout: default
permalink: /docs/
title: Event sourcing for PHP
---

# What is EventSauce?

EventSauce is a no-nonsense event sourcing library for PHP with a focus on developer
experience and productivity. This library was developed with the idea that you should
be able to add event sourced parts to your application with ease. No application-wide
rewrites and no big investments upfront.

The core is built around a set of (tiny) interfaces, which gives you the freedom
to choose the tools that meet your requirements.

EventSauce puts the focus on event sourcing, not on things that happen around event
sourcing. It does not require you to follow CQRS patterns. It does not require you
to use a command-, event-, or query-bus. By doing so, it allows developers to use
event sourcing for parts of their application more easily.

## Why _not_ use EventSauce?

The library is focused purely around event sourcing, **not** full-blown CQRS/ES. If
you need that, I recommend checking out [Prooph](https://github.com/prooph) or
[Broadway](https://github.com/broadway/broadway). These libraries have a higher level
of entry but provide an "everything you need is right here" experience.

