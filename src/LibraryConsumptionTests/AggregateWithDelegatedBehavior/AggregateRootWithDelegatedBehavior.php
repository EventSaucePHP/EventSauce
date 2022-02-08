<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\AggregateWithDelegatedBehavior;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootWithAggregates;

class AggregateRootWithDelegatedBehavior implements AggregateRoot
{
    use AggregateRootWithAggregates;

    private DelegatedBehaviorInAggregate $delegatedAggregate;

    protected function applyDelegatedAggregateWasChosen(DelegatedAggregateWasChosen $event): void
    {
        $this->delegatedAggregate = new DelegatedBehaviorInAggregate($this->eventRecorder());
        $this->registerAggregate($this->delegatedAggregate);
    }

    protected function applyDelegatedAggregateWasDiscarded(DelegatedAggregateWasDiscarded $event): void
    {
        $this->unregisterAggregate($this->delegatedAggregate);
        unset($this->delegatedAggregate);
    }

    public function causeDelegatedAction(): void
    {
        assert(isset($this->delegatedAggregate) && $this->delegatedAggregate instanceof DelegatedBehaviorInAggregate);
        $this->delegatedAggregate->performAction();
    }
}
