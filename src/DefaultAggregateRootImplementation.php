<?php

namespace EventSauce\EventSourcing;

use Generator;

trait DefaultAggregateRootImplementation
{
    private AggregateRootId $aggregateRootId;
    private int $aggregateRootVersion = 0;
    /** @var list<object> */
    private array $recordedEvents = [];

    /**
     * @see AggregateRoot::aggregateRootId
     */
    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    /**
     * @see AggregateRoot::aggregateRootVersion
     */
    public function aggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    protected function recordThat(object $event): void
    {
        $this->apply($event);
        $this->recordedEvents[] = $event;
    }

    /**
     * @see AggregateRoot::releaseEvents
     * @return object[]
     */
    public function releaseEvents(): array
    {
        $releasedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $releasedEvents;
    }

    abstract protected static function instantiateForReconstitution(AggregateRootId $aggregateRootId): static;

    /**
     * @see AggregateRoot::reconstituteFromEvents
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): static
    {
        $aggregateRoot = static::instantiateForReconstitution($aggregateRootId);

        /** @var object $event */
        foreach ($events as $event) {
            $aggregateRoot->apply($event);
        }

        $aggregateRoot->aggregateRootVersion = $events->getReturn() ?: 0;

        return $aggregateRoot;
    }
}
