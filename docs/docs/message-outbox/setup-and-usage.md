---
permalink: /docs/message-outbox/setup-and-usage/
redirect_from:
    - /docs/message-outbox/setup/
    - /docs/message-outbox/usage/
title: Message Outbox Setup and Usage
---

The outbox module consists of three parts, a _repository_, a _dispatcher_ and a
_relay_. The repository is responsible storing and retrieving  messages. The
dispatcher is responsible for putting messages  in the repository and hooks into
the core by implementing the `MessageDispatcher` interface.  The relay is
responsible for fetching messages from the repository and committing them (mark
as consumed or delete).

## Step 1: Set up your database schema

Your messages should be stored in the same database as our regular message
repository does. This may sound like we're needlessly storing twice but each
table has its own responsibility, and we interact with the in distinct ways.

Messages stored in the outbox table are expected to be deleted or marked as
consumed while our normal messages are intended to stay around for as long as
we need to rebuild our aggregates.

[Check out the database table schema](/docs/message-outbox/table-schema/).

## Step 2: Set up your Outbox Repository

Once you've set up the table schema you can bootstrap your Outbox Repository.
EventSauce supplies a couple of them:

- [Illuminate](/docs/message-outbox/illuminate/)
- [Doctrine 3](/docs/message-outbox/doctrine-3/)
- [Doctrine 2](/docs/message-outbox/doctrine-2/)

All the outbox repositories listed are compatible with documented the
[table schema](/docs/message-outbox/table-schema/).

## Step 3: Set up the message repository

Each of the supplied outbox repository packages also supplies a transactional
message repository implementation which ensures the messages are stored in
both repositories (outbox and normal for rebuilding) in a single transaction.

Generically speaking, the setup looks something like:

```php
new SomeTransactionalMessageRepository(
    connection: $databaseConnection,
    messageRepository: $messageRepository,
    outboxRepository: $outboxRepository,
);
```

Use this newly set up repository as your main Message Repository implementation
and you're all set on the dispatching side.

## Step 4: Set up the relay

The last thing to do, is to set up the relay mechanism. The relay is responsible for
reading the messages from the buffer table so they can be pushed onto the queue. The
relay takes your outbox repository and a consumer. This consumer (and implementation
of the `Consumer` interface) is tasked with forwarding the messages to your queueing
mechanism of choice.

```php
use EventSauce\MessageOutbox\OutboxRelay;

$relay = new OutboxRelay(
    $yourRepository,
    $forwardingMessageConsumer,
    $backOffStrategy, /* optional */
    $commitStrategy, /* optional */
);
```

The relay should run in a background process. You can hook the relay into your
(CLI) framework of choice. An implementation might look something like:

```php
use EventSauce\MessageOutbox\OutboxRelay;

class RelayOutboxMessagesCommand implements Command
{
    private bool $shouldRun = true;
    public function __construct(private OutboxRelay $relay) {}
    public function main(): int
    {
        while($this->shouldRun) {
            $numberOfMessagesDispatched = $this->relay->publishBatch(
                batchSize: 100,
                commitSize: 1,
            );
            
            if ($numberOfMessagesDispatched === 0) {
                // when no messages are relayed, sleep to prevent infrastructure hammering
                sleep(1);
            }
        }
    }
}
```

The `commitSize` can be increased to increase throughput. When doing so, it may increase
the likeliness of dispatching the same message to the queue twice in case of network failure.

### Outbox Message Dispatcher

The outbox package provides a `MessageDipatcher` implementation
that forwards the messages to the outbox repository. This implementation
is useful when want to leverage EventSauce's dispatcher capabilities in
a non-event-sourced context.

```php
use EventSauce\EventSourcing\Message;
use EventSauce\MessageOutbox\OutboxMessageDispatcher;

$messageDispatcher = new OutboxMessageDispatcher($yourRepository);

$messageDispatcher->dispatch(new Message(new YourEvent(1234)));
```

### Back-off Strategy

A back-off strategy may be specified. By default, an exponential
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
