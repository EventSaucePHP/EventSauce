<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ComplexAggregates;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootWithAggregates;

class ComplexAggregateRoot implements AggregateRoot
{
    use AggregateRootWithAggregates;

    /**
     * @var DelegatedBehaviorInAggregate
     */
    private $delegatedAggregate;

    protected function applyDelegatedAggregateWasChosen(DelegatedAggregateWasChosen $event)
    {
        $this->delegatedAggregate = new DelegatedBehaviorInAggregate($this->eventRecorder());
        $this->registerAggregate($this->delegatedAggregate);
    }

    protected function applyDelegatedAggregateWasDiscarded(DelegatedAggregateWasDiscarded $event)
    {
        $this->unregisterAggregate($this->delegatedAggregate);
    }

    public function causeDelegatedAction(): void
    {
        assert($this->delegatedAggregate instanceof DelegatedBehaviorInAggregate);
        $this->delegatedAggregate->performAction();
    }
}
