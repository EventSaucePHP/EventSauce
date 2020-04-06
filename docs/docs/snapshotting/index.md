---
permalink: /docs/snapshotting/
title: Snapshotting
published_at: 2019-09-25
updated_at: 2020-03-02
---

Snapshotting is a way to reduce the amount of events you need to load and apply when fetching
your aggregate root. A snapshot represents the decision model at a given version. When you have
a large number of events per aggregate root, it might be something to consider. Keep in mind that
it comes with some challenges of its own.

## How is a snapshot created?

After reconstituting an aggregate from all the relevant events, a snapshot can be created. Without
going into detail now, the aggregate's internals are used to represent its current state. This state
is persisted and can be retrieved at a later point in time. Alongside the snapshot state, a version
is stored. A snapshot contains:

- The aggregate root ID
- The snapshot state
- The aggregate root version

## Aggregate Reconstitution from a Snapshot

A snapshot can be retrieved by the ID of an aggregate root, similar to how you'd normally retrieve
the aggregate root itself. The `MessageRepository` is used to retrieve any events that have happened
_after_ the snapshot was stored. The snapshot and the events are then passed to the aggregate's snapshot
reconstitution method. First the aggregate root creates a new instance of itself using the aggregate ID
and the snapshot state. When that is completed, the additional events are applied to the aggregate root.
This ensures the aggregate root is in the same state as it would be when reconstituted from the entire
stream of events.

## Versioning Snapshots

Snapshots are stored in the database. When your aggregate root evolves, so must your snapshots. A good practise is to
version your snapshots. Storing a version along with your snapshot allows you to filter out any outdated ones when
trying to fetch your aggregate root.
