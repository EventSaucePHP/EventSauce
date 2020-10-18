<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

/**
 * @see AggregateRoot
 */
trait AggregateRootBehaviour
{
    use AggregateAlwaysAppliesEvents {
        apply as protected;
    }

    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var int
     */
    private $aggregateRootVersion = 0;

    /**
     * @var object[]
     */
    private $recordedEvents = [];

    private function __construct(AggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

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
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot
    {
        /** @var AggregateRoot&static $aggregateRoot */
        $aggregateRoot = new static($aggregateRootId);

        /** @var object $event */
        foreach ($events as $event) {
            $aggregateRoot->apply($event);
        }

        $aggregateRoot->aggregateRootVersion = $events->getReturn() ?: 0;

        /* @var AggregateRoot $aggregateRoot */
        return $aggregateRoot;
    }
}
