<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

trait AggregateRootBehaviourWithRequiredHistory
{
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
