<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

trait AggregateRootBehaviourWithRequiredHistory
{
    use AggregateRootBehaviour {
        AggregateRootBehaviour::reconstituteFromEvents as private defaultAggregateRootReconstitute;
    }

    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot
    {
        $aggregateRoot = static::defaultAggregateRootReconstitute($aggregateRootId, $events);

        if (0 === $aggregateRoot->aggregateRootVersion()) {
            throw new InvalidAggregateRootReconstitutionException();
        }

        return $aggregateRoot;
    }
}
