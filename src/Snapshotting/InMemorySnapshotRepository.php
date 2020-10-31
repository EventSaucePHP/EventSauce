<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;

final class InMemorySnapshotRepository implements SnapshotRepository
{
    /**
     * @var array<string,Snapshot>
     */
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
