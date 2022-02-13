<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

/**
 * @see AggregateRoot
 */
trait AggregateRootBehaviour
{
    use AggregateAlwaysAppliesEvents;
    use DefaultAggregateRootImplementation;

    private function __construct(AggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

    /**
     * @see DefaultAggregateRootImplementation::instantiateForReconstitution()
     */
    protected static function instantiateForReconstitution(AggregateRootId $aggregateRootId): static
    {
        return new static($aggregateRootId);
    }
}
