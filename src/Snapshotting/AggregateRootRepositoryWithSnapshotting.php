<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;

interface AggregateRootRepositoryWithSnapshotting extends AggregateRootRepository
{
    public function retrieveFromSnapshot(AggregateRootId $aggregateRootId): object;

    public function storeSnapshot(AggregateRootWithSnapshotting $aggregateRoot): void;
}
