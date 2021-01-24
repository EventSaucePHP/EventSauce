<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;

/**
 * @template T of AggregateRootWithSnapshotting
 *
 * @template-extends AggregateRootRepository<T>
 */
interface AggregateRootRepositoryWithSnapshotting extends AggregateRootRepository
{
    /**
     * @phpstan-return AggregateRootWithSnapshotting
     */
    public function retrieveFromSnapshot(AggregateRootId $aggregateRootId): object;

    /**
     * @phpstan-param T $aggregateRoot
     */
    public function storeSnapshot(AggregateRootWithSnapshotting $aggregateRoot): void;
}
