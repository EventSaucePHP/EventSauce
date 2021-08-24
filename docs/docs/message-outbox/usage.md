---
permalink: /docs/message-outbox/usage/
title: Message Outbox- Usage
published_at: 2021-05-02
updated_at: 2021-05-05
---

The outbox module consists of three parts, a _repository_, a _dispatcher_
and a _relay_. The repository is responsible storing and retrieving
messages. The dispatcher is responsible for putting messages
in the repository and hooks into the core by implementing the `MessageDispatcher` interface.
The relay is responsible for fetching messages from the repository and committing them (mark as consumed or delete).

Once you've [setup](/docs/message-outbox/setup/) your
outbox repository, you're ready to use the outbox.

## Dispatcher Setup

The outbox package provides a `MessageDipatcher` implementation
that forwards the messages to the outbox repository.

```php
use EventSauce\EventSourcing\Message;
use EventSauce\MessageOutbox\OutboxMessageDispatcher;

$messageDispatcher = new OutboxMessageDispatcher($yourRepository);

$messageDispatcher->dispatch(new Message(new YourEvent(1234)));
```

## Relay Setup

```php
use EventSauce\MessageOutbox\OutboxRelay;

$relay = new OutboxRelay(
    $yourRepository,
    $forwardingMessageConsumer,
    $backOffStrategy, /* optional */
    $commitStrategy, /* optional */
);
```

### Back-off Strategy

A back-off strategy may be specified. By default an exponential
back-off strategy is used. For more information about the available
strategies and configuration options, view the [readme](https://github.com/EventSaucePHP/BackOff).

### Commit Strategy

Messages can be committed using two strategies:

- Mark message as consumed
- Delete the message

```php
$markAsConsumed = new EventSauce\MessageOutbox\MarkMessagesConsumedOnCommit();
$deleteMessage = new EventSauce\MessageOutbox\DeleteMessageOnCommit();
```
