---
permalink: /docs/message-outbox/setup/
title: Message Outbox - Setup
published_at: 2021-05-02
updated_at: 2021-05-02
---

At the time of writing, two outbox implementations are provided.

* [Doctrine](#doctrine)
* [Illuminate](#illuminate) (Laravel)

You can also [build your own implementation](/docs/message-outbox/usage/build-your-own/)

## Database Schema

Outbox setups come in all shapes and sized. Unless you know what you're
doing, you're advised to default to one outbox per aggregate root type.

Below is a highly optimized database schema, perfect for an outbox table:

```text
CREATE TABLE IF NOT EXISTS `outbox_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `consumed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `payload` varchar(16001) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_consumed` (`consumed`, `id` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

The `id` field is a `BIGINT`, auto-incrementing, used for sorting
and marking messages as consumed. The `consumed` field is a tinyint(1)
used as a filter to exclude previously consumed messages. The `payload`
field is a `VARCHAR`, used to store the JSON blob in. The payload is
stored as a `VARCHAR` because `BLOB` or `JSON` fields store their data
separate from the row, which is less performant.

## Repository Installation &amp; Setup

### Doctrine

```bash
composer require eventsauce/message-outbox-for-doctrine
```

```php
use EventSauce\MessageOutbox\DoctrineMessageOutbox\DoctrineMessageOutboxRepository;
use EventSauce\MessageOutbox\OutboxMessageDispatcher;

$outboxRepository = new DoctrineMessageOutboxRepository($connection, $tableName, $messageSerializer);
$messageDispatcher = new OutboxMessageDispatcher($outboxRepository);
```

### Illuminate

```bash
composer require eventsauce/message-outbox-for-illuminate
```

```php
use EventSauce\MessageOutbox\IlluminateMessageOutbox\IlluminateMessageOutboxRepository;
use EventSauce\MessageOutbox\OutboxMessageDispatcher;

$outboxRepository = new IlluminateMessageOutbox($connection, $tableName, $messageSerializer);
$messageDispatcher = new OutboxMessageDispatcher($outboxRepository);
```


