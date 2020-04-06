---
permalink: /docs/installation/
title: Installation
published_at: 2018-02-21
updated_at: 2020-03-28
---

EvenSauce consists of multiple parts. Besides the main package you'll need _persistence_ and a _dispatcher_.

First you'll need to install the main package:

```bash
composer require eventsauce/eventsauce
```

There is test tooling available, install it using:

```bash
composer require --dev eventsauce/test-utilities
```

To use code generation, install it by running:

```bash
composer require --dev eventsauce/code-generation
```

At the time of writing a Doctrine implementation of the `MessageRepository` is provided separately:

```bash
composer require eventsauce/doctrine-message-repository
```

There's also a RabbitMQ dispatcher available. This package is an extension to the php-amqplib/rabbitmq-bundle package.
This is a Symfony specific package which offers a solid integration with the framework. This package provides an
implementation of the `EventSauce\EventSourcing\MessageConsumer` interface, binding it to the
`OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface` which ties into the bundle.

```bash
composer require eventsauce/rabbitmq-bundle-bindings
```
