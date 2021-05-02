<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use SplObjectStorage;

/**
 * @template T of EventSourcedAggregate
 */
trait AggregateRootWithAggregates
{
    use AggregateRootBehaviour, AggregateAppliesKnownEvents {
        AggregateAppliesKnownEvents::apply as applyOnAggregateRoot;
    }

    /**
     * @var ?SplObjectStorage<T>
     */
    private ?SplObjectStorage $aggregatesInsideRoot = null;
    private ?EventRecorder $eventRecorder = null;

    protected function eventRecorder(): EventRecorder
    {
        if (null === $this->eventRecorder) {
            $this->eventRecorder = new EventRecorder(fn (object $event) => $this->recordThat($event));
        }

        return $this->eventRecorder;
    }

    /**
     * @return SplObjectStorage<T>
     */
    private function aggregatesInsideRoot(): SplObjectStorage
    {
        if (null === $this->aggregatesInsideRoot) {
            $this->aggregatesInsideRoot = new SplObjectStorage();
        }

        return $this->aggregatesInsideRoot;
    }

    private function registerAggregate(?EventSourcedAggregate $aggregate): void
    {
        if ($aggregate instanceof EventSourcedAggregate) {
            $this->aggregatesInsideRoot()->attach($aggregate);
        }
    }

    private function unregisterAggregate(?EventSourcedAggregate $aggregate): void
    {
        if ($aggregate instanceof EventSourcedAggregate) {
            $this->aggregatesInsideRoot()->detach($aggregate);
        }
    }

    protected function apply(object $event): void
    {
        $this->applyOnAggregateRoot($event);

        /** @var EventSourcedAggregate $aggregate */
        foreach ($this->aggregatesInsideRoot() as $aggregate) {
            $aggregate->apply($event);
        }
    }
}
