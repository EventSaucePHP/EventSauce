---
permalink: /docs/message-outbox/doctrine-2/
title: Message Outbox for Doctrine 2
published_at: 2021-08-24
updated_at: 2021-08-24
---

[![Packagist Version](https://img.shields.io/packagist/v/eventsauce/message-outbox-for-doctrine-v2.svg?style=flat-square)](https://packagist.org/packages/eventsauce/message-outbox-for-doctrine-v2)

```bash
composer require eventsauce/message-outbox-for-doctrine-v2
```

```php
use EventSauce\MessageOutbox\DoctrineV2Outbox\DoctrineOutboxRepository;
use EventSauce\MessageOutbox\OutboxMessageDispatcher;

$outboxRepository = new DoctrineOutboxRepository($connection, $tableName, $messageSerializer);
$messageDispatcher = new OutboxMessageDispatcher($outboxRepository);
```

To ensure dispatching and persisting of messages is done in a single transaction, use
the transactional repository wrapper:

```php
use EventSauce\MessageOutbox\DoctrineV2Outbox\DoctrineTransactionalMessageRepository;

$messageRepository = new DoctrineTransactionalMessageRepository(
    $connection,
    $innerMessageRepository, // based which uses the same db connection,
    $messageSerializer,
);
```
