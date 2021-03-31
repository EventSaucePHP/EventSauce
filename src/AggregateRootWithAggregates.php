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

    private function aggregatesInsideRoot(): SplObjectStorage
    {
        if ($this->aggregatesInsideRoot instanceof SplObjectStorage) {
            return $this->aggregatesInsideRoot;
        }

        return $this->aggregatesInsideRoot = new SplObjectStorage();
    }

    private function registerAggregate(?EventSourcedAggregate $aggregate): void
    {
        if ($aggregate instanceof EventSourcedAggregate) {
            $storage = $this->aggregatesInsideRoot();
            $storage->attach($aggregate);
        }
    }

    private function unregisterAggregate(?EventSourcedAggregate $aggregate): void
    {
        if ($aggregate instanceof EventSourcedAggregate) {
            $storage = $this->aggregatesInsideRoot();
            $storage->detach($aggregate);
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
