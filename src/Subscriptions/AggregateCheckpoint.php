<?php

namespace EventSauce\EventSourcing\Subscriptions;

use EventSauce\EventSourcing\AggregateRootId;

final class AggregateCheckpoint implements Checkpoint
{
    private function __construct(
        private AggregateRootId $aggregateRootId,
        private int $aggregateRootVersion,
    ) {
    }

    public static function forAggregateRootId(AggregateRootId $aggregateRootId, int $version = 0): static
    {
        return new static($aggregateRootId, $version);
    }

    public function getAggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function getVersion(): int
    {
        return $this->aggregateRootVersion;
    }

}
