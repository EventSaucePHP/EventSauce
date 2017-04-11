<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\Time\Clock;

class DummyAggregate extends AggregateRoot
{
    private $incrementedNumber = 0;

    public function performDummyTask(Clock $clock)
    {
        $this->recordThat(new DummyTaskWasExecuted(
            $this->aggregateRootId(),
            $clock->pointInTime()
        ));
    }

    public function increment(Clock $clock)
    {
        $this->recordThat(new DummyIncrementingHappened(
            $this->aggregateRootId(),
            $clock->pointInTime(),
            $this->incrementedNumber + 1
        ));
    }

    protected function applyDummyIncrementingHappened(DummyIncrementingHappened $event)
    {
        $this->incrementedNumber = $event->number();
    }

    protected function applyDummyTaskWasExecuted(DummyTaskWasExecuted $event)
    {

    }

    public function dontDoAnything()
    {
        // not doing anything.
    }

    public function throwAnException()
    {
        throw new DummyException();
    }
}