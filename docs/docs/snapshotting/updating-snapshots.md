---
permalink: /docs/snapshotting/updating-snapshots/
title: Updating Snapshots
published_at: 2019-09-25
updated_at: 2020-03-02
---

When using snapshotting, you'll need to update snapshots every once in a while
to keep up with your event stream. There are two strategies you can apply to
update snapshots.

> 1. Update snapshot from older snapshot.
> 2. Create new snapshot from a clean event stream.

The snapshotting implementation makes a clear distinction between regular reconstitution
and reconstitution from a snapshot. This makes it clear what is happening and allows you
to choose what is best for your situation.

## 1. Update snapshot from an existing snapshot.

When updating a snapshot from an existing snapshot, you'll first be retrieving an aggregate
using a snapshot. Any new events will be applied to the aggregate in the process. You can
then overwrite the existing snapshot with the updated one. This process is quick and straight
forward.

```php
$aggregate = $aggregateRepository->retrieveFromSnapshot($id);
$aggregateRepository->storeSnapshot($aggregate);
```

## 2. Create new snapshot from a clean event stream.

In some cases this strategy might not be the best for you. You may want to re-build the aggregate
from scratch based on the full event stream. For this you can use the following code:

```php
$aggregate = $aggregateRepository->retrieve($id);
$aggregateRepository->storeSnapshot($aggregate);
```
