<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;

class DummyAggregate implements AggregateRoot
{
    use AggregateRootBehaviour;

    private $incrementedNumber = 0;

    public static function create(DummyAggregateRootId $aggregateRootId)
    {
        $aggregate = new static($aggregateRootId);
        $aggregate->recordThat(new AggregateWasInitiated());

        return $aggregate;
    }

    protected function applyAggregateWasInitiated()
    {
        // cool
    }

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

    protected function applyDummyTaskWasExecuted(/** @scrutinizer ignore-unused */ DummyTaskWasExecuted $event)
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
