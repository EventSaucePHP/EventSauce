<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRoot;
use Generator;

interface AggregateRootWithSnapshotting extends AggregateRoot
{
    public function createSnapshot(): Snapshot;

    /**
     * @return static
     */
    public static function reconstituteFromSnapshotAndEvents(Snapshot $snapshot, Generator $events): AggregateRoot;
}
