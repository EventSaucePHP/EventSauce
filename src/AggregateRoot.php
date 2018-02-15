<?php

namespace EventSauce\EventSourcing;

use Generator;

interface AggregateRoot
{
    /**
     * @return AggregateRootId
     */
    public function aggregateRootId(): AggregateRootId;

    /**
     * @return Event[]
     */
    public function releaseEvents(): array;

    /**
     * @param AggregateRootId $aggregateRootId
     * @param Generator           $events
     *
     * @return static
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot;
}