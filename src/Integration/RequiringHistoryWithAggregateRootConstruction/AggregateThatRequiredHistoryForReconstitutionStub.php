<?php

namespace EventSauce\EventSourcing\Integration\RequiringHistoryWithAggregateRootConstruction;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviourWithRequiredHistory;
use EventSauce\EventSourcing\Integration\DummyAggregateRootId;

class AggregateThatRequiredHistoryForReconstitutionStub implements AggregateRoot
{
    use AggregateRootBehaviourWithRequiredHistory;

    public static function start(DummyAggregateRootId $id)
    {
        $aggregate = new static($id);
        $aggregate->recordThat(new DummyInternalEvent());

        return $aggregate;
    }

    protected function applyDummyInternalEvent(DummyInternalEvent $event)
    {
        // can be ignored
    }
}
