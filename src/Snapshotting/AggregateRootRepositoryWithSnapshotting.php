<?php


namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootRepository;

interface AggregateRootRepositoryWithSnapshotting extends AggregateRootRepository
{
    public function storeSnapshot(AggregateRootWithSnapshotting $aggregateRoot): void;
}
