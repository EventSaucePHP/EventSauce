---
permalink: /docs/message-outbox/table-schema/
title: Message Outbox Table Schema
---

Outbox setups come in all shapes and sizes. Unless you know what you're
doing, you're advised to default to one outbox per aggregate root type.

Below is a highly optimized database schema, perfect for an outbox table:

```text
CREATE TABLE IF NOT EXISTS `outbox_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `consumed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `payload` varchar(16001) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_consumed` (`consumed`, `id` ASC)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB;
```

The `id` field is a `BIGINT`, auto-incrementing, used for sorting
and marking messages as consumed. The `consumed` field is a tinyint(1)
used as a filter to exclude previously consumed messages. The `payload`
field is a `VARCHAR`, used to store the JSON blob in. The payload is
stored as a `VARCHAR` because `BLOB` or `JSON` fields store their data
separate from the row, which is less performant.
