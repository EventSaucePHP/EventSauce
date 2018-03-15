---
permalink: /docs/
title: Event sourcing for PHP
hide_title: true
published_at: 2018-03-11
updated_at: 2018-03-15
---

<div class="text-center mb-8 max-w-md">
    <img id="logo" src="/static/logo.svg" height="150px" width="150px" alt="EventSauce">
    <h1 class="text-grey-darkest mt-1">
        Event<span class="text-red">Sauce</span>
    </h1>
</div>

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/EventSaucePHP/EventSauce/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/EventSaucePHP/EventSauce/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/EventSaucePHP/EventSauce/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/EventSaucePHP/EventSauce/?branch=master)
[![Build Status](https://travis-ci.org/EventSaucePHP/EventSauce.svg?branch=master)](https://travis-ci.org/EventSaucePHP/EventSauce)


# What is EventSauce?

EventSauce is a no-nonsense event sourcing library for PHP with a focus on developer
experience and productivity. This library was developed with the idea that you should
be able to add event sourced parts to your application with ease. No application-wide
rewrites and no big investments upfront. The core is built around a set of (tiny)
interfaces, which gives you the freedom to choose the tools that meet your requirements.

Many parts of the library are extremely pragmatic in nature. You're encouraged to take
control over it. It allows everything from  custom storage adapter to highly customizable
message dispatching setups.

EventSauce puts the focus on event sourcing, not on things that happen around event
sourcing. It does not require you to follow CQRS patterns. It does not require you
to use a command-, event-, or query-bus. By doing so, it allows developers to use
event sourcing for parts of their application more easily.

## Why _not_ use EventSauce?

The library is focused purely around event sourcing, **not** full-blown CQRS/ES. If
you need that, I recommend checking out [Prooph](https://github.com/prooph) or
[Broadway](https://github.com/broadway/broadway). These libraries have a higher level
of entry but provide an "everything you need is right here" experience.

## Disclaimer

Solving problems using event sourcing requires a very different mindset when compared
to traditional PHP-OOP software modeling. It's less focused about state and more
about processes, transitions, and communication in general. Event sourcing is also not
simple. It's build on a body of knowledge that is inherently complex. There are many
concepts that come into play that build off one another.

However, event sourcing also provides an easier way to model a variety of issues. It's
a remedy against storing "work in progress" entities. It's a better fit when modeling
anything where the transition is just as important as the end result.
