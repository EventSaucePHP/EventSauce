<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;

final class Snapshot
{
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

    public function aggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    public function state(): mixed
    {
        return $this->state;
    }
}
