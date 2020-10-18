<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ComplexAggregates;

use EventSauce\EventSourcing\AggregateAppliesKnownEvents;
use EventSauce\EventSourcing\EventRecorder;
use EventSauce\EventSourcing\EventSourcedAggregate;

class DelegatedBehaviorInAggregate implements EventSourcedAggregate
{
    use AggregateAppliesKnownEvents;

    /**
     * @var EventRecorder
     */
    private $eventRecorder;

    private $counter = 0;

    public function __construct(EventRecorder $eventRecorder)
    {
        $this->eventRecorder = $eventRecorder;
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
