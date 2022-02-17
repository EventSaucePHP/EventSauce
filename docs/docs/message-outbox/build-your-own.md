---
permalink: /docs/message-outbox/build-your-own/
title: Message Outbox - Build your own
---

Not all database libraries are supported, but that should not limit
you from using one. You can create your own integration. To do so,
your implementation needs to satisfy the correct interface.

```php
use EventSauce\EventSourcing\Message;
use EventSauce\MessageOutbox\MessageOutboxRepository;

class MyCustomMessageOutboxRepository implements MessageOutboxRepository
{
    public function persist(Message ...$messages): void
    {
        // IMPLEMENT ME
    }

    public function retrieveBatch(int $batchSize): Traversable
    {
        // IMPLEMENT ME
    }

    public function markConsumed(Message ...$messages): void
    {
        // IMPLEMENT ME
    }

    public function deleteMessages(Message ...$messages): void
    {
        // IMPLEMENT ME
    }

    public function cleanupConsumedMessages(int $amount): int
    {
        // IMPLEMENT ME
    }

    public function numberOfMessages(): int
    {
        // IMPLEMENT ME
    }

    public function numberOfConsumedMessages(): int
    {
        // IMPLEMENT ME
    }

    public function numberOfPendingMessages(): int
    {
        // IMPLEMENT ME
    }
}
```
