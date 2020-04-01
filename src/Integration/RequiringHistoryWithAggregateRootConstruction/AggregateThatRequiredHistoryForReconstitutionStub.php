<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\RequiringHistoryWithAggregateRootConstruction;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviourWithRequiredHistory;
use EventSauce\EventSourcing\DummyAggregateRootId;

class AggregateThatRequiredHistoryForReconstitutionStub implements AggregateRoot
{
    use AggregateRootBehaviourWithRequiredHistory;

    public static function start(DummyAggregateRootId $id)
    {
        $aggregate = new static($id);
        $aggregate->recordThat(new DummyInternalEvent());

        return $aggregate;
    }

    protected function applyDummyInternalEvent(DummyInternalEvent $event): void
    {
        // can be ignored
    }
}
