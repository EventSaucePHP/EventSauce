---
permalink: /docs/message-storage/repository-table-schema/
title: Message Repository Table Schema
---

[![Packagist Version](https://img.shields.io/packagist/v/eventsauce/message-repository-table-schema.svg?style=flat-square)](https://packagist.org/packages/eventsauce/message-repository-table-schema)

```bash
composer require eventsauce/message-repository-table-schema
```

The table schema allows you to specify and customize the column names used for you
message repository table. Additionally, it allows you to add additional headers as
fields to each row.

### Default Table Schema

The default implementation `DefaultTableSchema` uses the following column names:

- `event_id` primary key (text/UUID)
- `aggregate_root_id` aggregate root ID (text/UUID)
- `version` aggregate root version (int)
- `payload` encoded event payload (text/JSON)

Which corresponds to the following table schema: 

```sql
CREATE TABLE IF NOT EXISTS `your_table_name` (
  `event_id` BINARY(16) NOT NULL,
  `aggregate_root_id` BINARY(16) NOT NULL,
  `version` int(20) unsigned NULL,
  `payload` varchar(16001) NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY (`aggregate_root_id`),
  KEY `reconstitution` (`aggregate_root_id`, `version` ASC)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB;
```

### Legacy Table Schema

> NOTE: The legacy schema requires the [string UUID encoder](/docs/message-storage/uuid-encoding/#string-uuid-encoder).


For users upgrading from EventSauce pre-1.0, there is a `LegacyTableSchema`:

- `event_id` primary key (text/UUID)
- `event_type` the serialized event name (text)
- `aggregate_root_id` aggregate root ID (text/UUID)
- `aggregate_root_version` aggregate root version (int)
- `time_of_recording` when the event was written (timestamp)
- `payload` encoded event payload (text/JSON)

Which corresponds to the following table schema:

```sql
CREATE TABLE IF NOT EXISTS your_table_name (
    event_id VARCHAR(36) NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    aggregate_root_id VARCHAR(36) NOT NULL,
    aggregate_root_version MEDIUMINT(36) UNSIGNED NOT NULL,
    time_of_recording DATETIME(6) NOT NULL,
    payload JSON NOT NULL,
    INDEX aggregate_root_id (aggregate_root_id),
    UNIQUE KEY unique_id_and_version (aggregate_root_id, aggregate_root_version ASC)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB
```

### Custom Implementations

Custom implementations of `TableSchema` can use the `additionalColumns` method to
write other `Header` values to columns, which can be useful for indexing.
