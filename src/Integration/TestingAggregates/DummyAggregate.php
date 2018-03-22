<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\BaseAggregateRoot;
use EventSauce\EventSourcing\ReconstitutableAggregateRoot;

class DummyAggregate extends BaseAggregateRoot implements ReconstitutableAggregateRoot
{
    private $incrementedNumber = 0;

    public function performDummyTask()
    {
        $this->recordThat(new DummyTaskWasExecuted());
    }

    public function increment()
    {
        $this->recordThat(new DummyIncrementingHappened(
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
