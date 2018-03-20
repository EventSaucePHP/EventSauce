<?php

namespace EventSauce\EventSourcing;

use Generator;

interface AggregateRootFactory
{
    public function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot;
}