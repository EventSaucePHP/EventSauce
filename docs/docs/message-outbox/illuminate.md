---
permalink: /docs/message-outbox/illuminate/
title: Message Outbox for Illuminate
---

[![Packagist Version](https://img.shields.io/packagist/v/eventsauce/message-outbox-for-illuminate.svg?style=flat-square)](https://packagist.org/packages/eventsauce/message-outbox-for-illuminate)

```bash
composer require eventsauce/message-outbox-for-illuminate
```

```php
use EventSauce\MessageOutbox\IlluminateOutbox\IlluminateOutboxRepository;
use EventSauce\MessageOutbox\OutboxMessageDispatcher;

$outboxRepository = new IlluminateOutboxRepository($connection, $tableName, $messageSerializer);
$messageDispatcher = new OutboxMessageDispatcher($outboxRepository);
```

To ensure dispatching and persisting of messages is done in a single transaction, use
the transactional repository wrapper:

```php
use EventSauce\MessageOutbox\IlluminateOutbox\IlluminateTransactionalMessageRepository;

$messageRepository = new IlluminateTransactionalMessageRepository(
    $connection,
    $innerMessageRepository, // based which uses the same db connection,
    $messageSerializer,
);
```
