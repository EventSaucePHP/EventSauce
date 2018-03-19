<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

interface AggregateRoot
{
    /**
     * @return AggregateRootId
     */
    public function aggregateRootId(): AggregateRootId;

    /**
     * @return int
     */
    public function aggregateRootVersion(): int;

    /**
     * @return Event[]
     */
    public function releaseEvents(): array;

    /**
     * @param AggregateRootId $aggregateRootId
     * @param Generator       $events
     *
     * @return AggregateRoot
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot;
}
