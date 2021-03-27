<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use SplObjectStorage;

trait AggregateRootWithAggregates
{
    use AggregateRootBehaviour, AggregateAppliesKnownEvents {
        AggregateAppliesKnownEvents::apply as applyOnAggregateRoot;
    }

    /**
     * @var SplObjectStorage|null
     */
    private $aggregatesInsideRoot = null;

    protected function eventRecorder(): EventRecorder
    {
        static $eventRecorder;

        if (null === $eventRecorder) {
            $eventRecorder = new EventRecorder(fn (object $event) => $this->recordThat($event));
        }

        return $eventRecorder;
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
