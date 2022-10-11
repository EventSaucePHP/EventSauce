---
permalink: /docs/advanced/replaying-messages/
title: Replaying Messages
redirect_from: /docs/advanced/rebuilding-projections/
---

In some cases, replaying messages that were stored previously is needed. It could be needed
for rebuilding projections, or re-dispatching messages for a consumer that previously had not
received the messages due to infrastructure failure. Regardless of the reason, EventSauce
provides a pragmatic default approach to re-consuming historic messages.

## Paginated replaying with cursors

EventSauce uses cursor-based pagination to feed messages into consumers. The design allows you
to set up routines that can rebuild projections that cross multiple deployments. By using a
cursor, the tooling can store any type of offset or filter when the process is signalled to stop.

A basic message replaying setup looks like:

```php
use EventSauce\EventSourcing\ReplayingMessages\ReplayMessages;
use EventSauce\EventSourcing\OffsetCursor;

$replayMessages = new ReplayMessages(
    $yourMessageRepositoryImplementation,
    $yourConsumerOrConsumerChain,
);

$cursor = OffsetCursor::fromStart(limit: 100);

process_batch:
$result = $replayMessages->replayBatch($cursor);
$cursor = $result->cursor();

if ($result->messagesHandled() > 0) {
    goto process_batch;
}
```

## Replay lifecycle hooks

The consumer can execute logic at the start and at the end of replaying. To enable this,
implement the `TriggerBeforeReplay` and/or `TriggerAfterReplay` interfaces.

```php
class MyConsumer implements MessageConsumer, TriggerBeforeReplay, TriggerAfterReplay
{
    public function consume(Message $message): void
    {
        // ...
    }

    public function beforeReplay(): void
    {
        // ...
    }

    public function afterReplay(): void
    {
        // ...
    }
}
```
