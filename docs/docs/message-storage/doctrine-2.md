---
permalink: /docs/message-storage/doctrine-2/
title: Message Repository for Doctrine DBAL 2
published_at: 2021-08-24
updated_at: 2021-08-24
---

[![Packagist Version](https://img.shields.io/packagist/v/eventsauce/message-repository-for-doctrine-v2.svg?style=flat-square)](https://packagist.org/packages/eventsauce/message-repository-for-doctrine-v2)

```bash
composer require eventsauce/message-repository-for-doctrine
```

```php
use EventSauce\MessageRepository\DoctrineV2MessageRepository\DoctrineUuidV4MessageRepository;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use EventSauce\UuidEncoding\BinaryUuidEncoder;

$messageRepository = new DoctrineUuidV4MessageRepository(
    connection: $doctrineDbalConnection,
    tableName: $tableName,
    serializer: $eventSauceMessageSerializer,
    tableSchema: new DefaultTableSchema(), // optional
    uuidEncoder: new BinaryUuidEncoder(), // optional
);
```
