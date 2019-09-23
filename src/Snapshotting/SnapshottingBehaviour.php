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
        $aggregateRoot = new static($snapshot->aggregateRootId());
        $aggregateRoot->setPayloadState($snapshot->state());

        foreach ($events as $event) {
            $aggregateRoot->apply($event);
        }

        $aggregateRoot->aggregateRootVersion = $events->getReturn() ?: $snapshot->aggregateRootVersion();

        return $aggregateRoot;
    }

    abstract protected function setPayloadState($state): void;
}
