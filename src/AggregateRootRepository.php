<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface AggregateRootRepository
{
    public function retrieve(AggregateRootId $aggregateRootId): object;

    public function persist(object $aggregateRoot);

    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events);
}
