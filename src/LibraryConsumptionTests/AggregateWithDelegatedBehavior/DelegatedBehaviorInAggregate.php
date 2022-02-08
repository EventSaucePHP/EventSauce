<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\AggregateWithDelegatedBehavior;

use EventSauce\EventSourcing\AggregateAppliesKnownEvents;
use EventSauce\EventSourcing\EventRecorder;
use EventSauce\EventSourcing\EventSourcedAggregate;

class DelegatedBehaviorInAggregate implements EventSourcedAggregate
{
    use AggregateAppliesKnownEvents;

    private int $counter = 0;

    public function __construct(private EventRecorder $eventRecorder)
    {
    }

    public function performAction(): void
    {
        $this->eventRecorder->recordThat(new DelegatedActionWasPerformed($this->counter + 1));
    }

    protected function applyDelegatedActionWasPerformed(DelegatedActionWasPerformed $event): void
    {
        $this->counter = $event->counter();
    }
}
