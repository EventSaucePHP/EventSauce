---
permalink: /docs/upgrading/to-0-7-0
title: Upgrade to 0.7.0
published_at: 2019-09-28
updated_at: 2019-12-21
---

## MessageRepository version handling.

The implementations of the `MessageRepository` interface are now expected to
return the latest version number as the `Generator` return value. This can be done
by returning it within the function you `yield` the messages from.

An simplified example:

```php
function getMessages(AggregateRootId $id): Generator
{
  foreach (fetchFromStorage($id) as $row) {
     yield convertRowToMessage($row);
  }

  return isset($row) ? $row['version'] : 0;
}
```

## MessageRepository fetch after specified version.

The `MessageRepository` interface has a new method, called `retrieveAllAfterVersion`. This
method is expected to return all the messages after a given aggregate root version. Adding this
method will ensure your application is prepared for snapshotting.
