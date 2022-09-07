<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

/**
 * @see AggregateRootBehaviour
 */
interface AggregateRoot
{
    public function aggregateRootId(): AggregateRootId;

    public function aggregateRootVersion(): int;

    /**
     * @return object[]
     */
    public function releaseEvents(): array;

    /**
     * @param Generator<int, object, void, int> $events
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): static;
}
