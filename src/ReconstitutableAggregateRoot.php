<?php

namespace EventSauce\EventSourcing;

use Generator;

interface ReconstitutableAggregateRoot
{
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot;
}