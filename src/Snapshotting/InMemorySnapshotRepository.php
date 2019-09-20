<?php

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;

class InMemorySnapshotRepository implements SnapshotRepository
{
    private $snapshots = [];

    public function persist(Snapshot $snapshot): void
    {
        $this->snapshots[$snapshot->aggregateRootId()->toString()] = $snapshot;
    }

    public function retrieve(AggregateRootId $id): ?Snapshot
    {
        return $this->snapshots[$id->toString()] ?? null;
    }
}
