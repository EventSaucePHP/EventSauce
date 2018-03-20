<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

class ReconstitutableAggregateRootFactory implements AggregateRootFactory
{
    /**
     * @var string
     */
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot
    {
        /** @var ReconstitutableAggregateRoot $className */
        $className = $this->className;

        return $className::reconstituteFromEvents($aggregateRootId, $events);
    }
}
