<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

interface ReconstitutableAggregateRoot
{
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot;
}
