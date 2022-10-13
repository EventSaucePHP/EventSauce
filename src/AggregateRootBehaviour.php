<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

/**
 * @template AggregateRootIdType of AggregateRootId
 * @see AggregateRoot
 */
trait AggregateRootBehaviour
{
    use AggregateAlwaysAppliesEvents;

    /** @var AggregateRootIdType */
    private AggregateRootId $aggregateRootId;
    /** @var 0|positive-int */
    private int $aggregateRootVersion = 0;
    /** @var object[] */
    private array $recordedEvents = [];

    /**
     * @param AggregateRootIdType $aggregateRootId
     */
    private function __construct(AggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

    /** 
     * @return AggregateRootIdType 
     */
    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    /**
     * @see AggregateRoot::aggregateRootVersion
     * @return 0|positive-int
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
     * @return object[]
     */
    public function releaseEvents(): array
    {
        $releasedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $releasedEvents;
    }

    /**
     * @see AggregateRoot::reconstituteFromEvents
     * @param AggregateRootIdType $aggregateRootId
     * @param Generator<int, object, void, int> $events
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): static
    {
        $aggregateRoot = static::createNewInstance($aggregateRootId);

        /** @var object $event */
        foreach ($events as $event) {
            $aggregateRoot->apply($event);
        }

        // @phpstan-ignore-next-line
        $aggregateRoot->aggregateRootVersion = $events->getReturn() ?: 0;

        return $aggregateRoot;
    }

    /**
     * @param AggregateRootIdType $aggregateRootId
     */
    private static function createNewInstance(AggregateRootId $aggregateRootId): static
    {
        return new static($aggregateRootId);
    }
}
