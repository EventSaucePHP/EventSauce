---
permalink: /docs/event-sourcing/configure-persistence/
redirect_from: /docs/getting-started/configure-persistence/
title: Configure Persistence
published_at: 2019-12-07
updated_at: 2020-04-06
---

EventSauce has _two_ connections to persistence.

* The `MessageRepository` which contains `Message`s for reconstituting aggregates.
* The `MessageDispatcher` which is used to communicate `Message`s with `MessageConsumer`s.

## `MessageRepository`

The message repository stores messages that aggregate roots use for reconstitution. The
repository has specific method to query messages that belong to a single aggregate root.
A message repository should only be used for reconstitution. Performing arbitrary queryies
on the underlying database is not advised, use a projection for this instead.

There are two message repository implementations shipped for v1:

* [Doctrine](#doctrine-message-repository)
* [Illuminate](#illuminate-message-repository)

### Schema

The recommended database schema for storing messages is:

```
CREATE TABLE IF NOT EXISTS `your_table_name` (
  `event_id` BINARY(16) NOT NULL,
  `aggregate_root_id` BINARY(16) NOT NULL,
  `version` int(20) unsigned NULL,
  `payload` varchar(16001) NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY (`aggregate_root_id`),
  KEY `reconstitution` (`aggregate_root_id`, `version` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

This database schema expects a UuidV4 `event_id` and `aggregate_root_id`, both
implementations will generate an `event_id` if none is provided.

### Doctrine Message Repository

```bash
composer require eventsauce/message-repository-for-doctrine
```

```php
use EventSauce\MessageRepository\DoctrineMessageRepository\DoctrineUuidV4MessageRepository;

$messageRepository = new DoctrineUuidV4MessageRepository($connection, $tableName, $messageSerializer);
```


### Illuminate Message Repository

```bash
composer require eventsauce/message-repository-for-illuminate
```

```php
use EventSauce\MessageRepository\IlluminateMessageRepository\IlluminateUuidV4MessageRepository;

$messageRepository = new IlluminateUuidV4MessageRepository($connection, $tableName, $messageSerializer);
```
