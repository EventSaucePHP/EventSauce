<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

/**
 * @template AggregateRootIdType of AggregateRootId
 *
 * @see AggregateRoot
 */
trait AggregateRootBehaviourWithRequiredHistory
{
    /** @phpstan-use AggregateRootBehaviour<AggregateRootIdType> */
    use AggregateRootBehaviour {
        AggregateRootBehaviour::reconstituteFromEvents as private defaultAggregateRootReconstitute;
    }

    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): static
    {
        $aggregateRoot = static::defaultAggregateRootReconstitute($aggregateRootId, $events);

        if ($aggregateRoot->aggregateRootVersion() === 0) {
            throw UnableToReconstituteAggregateRoot::becauseItHasNoHistory();
        }

        return $aggregateRoot;
    }
}
