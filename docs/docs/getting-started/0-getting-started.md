---
permalink: /docs/getting-started/introduction/
redirect_from: /docs/getting-started/
title: Getting Started
---

EventSauce is a pragmatic yet robust event sourcing library for PHP. At the heart of the library
are a set of simple interfaces that make it easy to adapt many types of infrastructure. A set of
ready-made components is there to speed up implementation. This guide will walk you through
the steps needed to set yourself up to start event sourcing. The guide will only focus on
what is relevant to setting up the library. Although there are some framework bindings available,
this guide will use a framework-agnostic point of view. You are expected to make the translation
on how to configure this in your framework of choice.

In this guide we'll walk to initial setup, setting up tests for your aggregates, and wiring it
to infrastructure. Let's get started!

## Installation

To get started, first install EventSauce into your PHP project using
[composer](https://getcomposer.org).

```bash
composer require eventsauce/eventsauce
```

This package contains the core of EventSauce, the interfaces, and generic tools.

Next up, create your first [aggregate root](/docs/getting-started/create-aggregate-root/)!
