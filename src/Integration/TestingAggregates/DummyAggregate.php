<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\BaseAggregateRoot;
use EventSauce\EventSourcing\Time\Clock;

class DummyAggregate extends BaseAggregateRoot
{
    private $incrementedNumber = 0;

    public function performDummyTask(Clock $clock)
    {
        $this->recordThat(new DummyTaskWasExecuted(
            $clock->pointInTime()
        ));
    }

    public function increment(Clock $clock)
    {
        $this->recordThat(new DummyIncrementingHappened(
            $clock->pointInTime(),
            $this->incrementedNumber + 1
        ));
    }

    public function emitSequence(Clock $clock)
    {
        $this->recordThat(new SequenceWasEmit($clock->pointInTime(), $this->aggregateRootVersion()));
    }

    protected function applySequenceWasEmit(SequenceWasEmit $event) {}

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