<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface AggregateRootRepository
{
    public function retrieve(AggregateRootId $aggregateRootId): object;

    /**
     * @return void
     */
    public function persist(object $aggregateRoot);

    /**
     * @return void
     */
    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events);
}
