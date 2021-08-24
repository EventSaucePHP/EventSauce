---
permalink: /docs/message-outbox/doctrine-3/
title: Message Outbox for Doctrine 3
published_at: 2021-08-24
updated_at: 2021-08-24
---

[![Packagist Version](https://img.shields.io/packagist/v/eventsauce/message-outbox-for-doctrine.svg?style=flat-square)](https://packagist.org/packages/eventsauce/message-outbox-for-doctrine)

```bash
composer require eventsauce/message-outbox-for-doctrine
```

```php
use EventSauce\MessageOutbox\DoctrineOutbox\DoctrineOutboxRepository;
use EventSauce\MessageOutbox\OutboxMessageDispatcher;

$outboxRepository = new DoctrineOutboxRepository($connection, $tableName, $messageSerializer);
$messageDispatcher = new OutboxMessageDispatcher($outboxRepository);
```

To ensure dispatching and persisting of messages is done in a single transaction, use
the transactional repository wrapper:

```php
use EventSauce\MessageOutbox\DoctrineOutbox\DoctrineTransactionalMessageRepository;

$messageRepository = new DoctrineTransactionalMessageRepository(
    $connection,
    $innerMessageRepository, // based which uses the same db connection,
    $messageSerializer,
);
```
