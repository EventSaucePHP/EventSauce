---
permalink: /docs/message-storage/illuminate/
title: Message Repository for Illuminate
published_at: 2021-08-24
updated_at: 2021-08-24
---

[![Packagist Version](https://img.shields.io/packagist/v/eventsauce/message-repository-for-illuminate.svg?style=flat-square)](https://packagist.org/packages/eventsauce/message-repository-for-illuminate)

```bash
composer require eventsauce/message-repository-for-illuminate
```

```php
use EventSauce\MessageRepository\IlluminateMessageRepository\IlluminateUuidV4MessageRepository;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use EventSauce\UuidEncoding\BinaryUuidEncoder;

$messageRepository = new IlluminateUuidV4MessageRepository(
    connection: $connection,
    tableName: $tableName,
    serializer: $messageSerializer,
    tableSchema: new DefaultTableSchema(), // optional
    uuidEncoder: new BinaryUuidEncoder(), // optional
);
```
