<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;

/**
 * @template S
 */
final class Snapshot
{
    /**
     * @param S $state
     * @param positive-int|0 $aggregateRootVersion
     */
    public function __construct(
        private AggregateRootId $aggregateRootId,
        private int $aggregateRootVersion,
        private mixed $state
    ) {
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    /**
     * @return positive-int|0
     */
    public function aggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    /**
     * @return S
     */
    public function state(): mixed
    {
        return $this->state;
    }
}
