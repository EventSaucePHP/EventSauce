<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

/**
 * @template AggregateRootIdType of AggregateRootId
 *
 * @see AggregateRootBehaviour
 */
interface AggregateRoot
{
    /**
     * @return AggregateRootIdType
     */
    public function aggregateRootId(): AggregateRootId;

    public function aggregateRootVersion(): int;

    /**
     * @return object[]
     */
    public function releaseEvents(): array;

    /**
     * @param AggregateRootIdType               $aggregateRootId
     * @param Generator<int, object, void, int> $events
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): static;
}
