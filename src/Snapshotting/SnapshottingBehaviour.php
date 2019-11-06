<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootId;
use Generator;

trait SnapshottingBehaviour
{
    /**
     * @var int
     */
    private $aggregateRootVersion = 0;

    abstract public function aggregateRootVersion(): int;

    abstract public function aggregateRootId(): AggregateRootId;

    abstract protected function apply(object $event): void;

    public function createSnapshot(): Snapshot
    {
        return new Snapshot($this->aggregateRootId(), $this->aggregateRootVersion(), $this->createSnapshotState());
    }

    abstract protected function createSnapshotState();

    /**
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

    abstract protected static function reconstituteFromSnapshotState(AggregateRootId $id, $state): AggregateRootWithSnapshotting;
}
