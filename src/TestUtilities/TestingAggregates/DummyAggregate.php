<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use EventSauce\EventSourcing\DummyAggregateRootId;

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

    protected function applyAggregateWasInitiated(): void
    {
        // cool
    }

    public function performDummyTask(): void
    {
        $this->recordThat(new DummyTaskWasExecuted());
    }

    public function increment(): void
    {
        $this->recordThat(
            new DummyIncrementingHappened(
                $this->incrementedNumber + 1
            )
        );
    }

    protected function applyDummyIncrementingHappened(DummyIncrementingHappened $event): void
    {
        $this->incrementedNumber = $event->number();
    }

    protected function applyDummyTaskWasExecuted(/* @scrutinizer ignore-unused */ DummyTaskWasExecuted $event): void
    {
    }

    public function dontDoAnything(): void
    {
        // not doing anything.
    }

    public function throwAnException(): void
    {
        throw new DummyException();
    }
}
