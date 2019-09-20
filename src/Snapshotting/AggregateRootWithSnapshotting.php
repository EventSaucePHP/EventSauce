<?php

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRoot;
use Generator;

interface AggregateRootWithSnapshotting extends AggregateRoot
{
    public function createSnapshot(): Snapshot;

    /**
     * @param Snapshot $snapshot
     * @param Generator       $events
     *
     * @return static
     */
    public static function reconstituteFromSnapshotAndEvents(Snapshot $snapshot, Generator $events): AggregateRoot;
}
