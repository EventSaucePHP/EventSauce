<?php

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootId;
use Generator;

trait SnapshottingBehaviour
{
    abstract public function aggregateRootVersion(): int;

    abstract public function aggregateRootId(): AggregateRootId;

    abstract protected function apply(object $event): void;

    public function createSnapshot(): Snapshot
    {
        return new Snapshot($this->aggregateRootId(), $this->aggregateRootVersion(), $this->createSnapshotState());
    }

    abstract protected function createSnapshotState();

    /**
     * @param Snapshot  $snapshot
     * @param Generator $events
     *
     * @return static
     */
    public static function reconstituteFromSnapshotAndEvents(Snapshot $snapshot, Generator $events): AggregateRoot
    {
        $id = $snapshot->aggregateRootId();
        /** @var static&AggregateRoot $aggregateRoot */
        $aggregateRoot = static::reconstituteFromSnapshotState($id, $snapshot->state());
        $aggregateRoot->aggregateRootVersion = $snapshot->aggregateRootVersion();

        foreach ($events as $event) {
            $aggregateRoot->apply($event);
        }

        $aggregateRoot->aggregateRootVersion = $events->getReturn() ?: $aggregateRoot->aggregateRootVersion;

        return $aggregateRoot;
    }

    abstract static protected function reconstituteFromSnapshotState(AggregateRootId $id, $state): AggregateRootWithSnapshotting;
}
