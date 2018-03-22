<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

interface AggregateRootFactory
{
    public function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot;
}
