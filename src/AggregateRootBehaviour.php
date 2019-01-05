<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

trait AggregateRootBehaviour
{
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

    public function __construct(AggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function aggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    protected function apply(object $event)
    {
        $parts = explode('\\', get_class($event));
        $this->{'apply' . end($parts)}($event);
        $this->aggregateRootVersion++;
    }

    protected function recordThat(object $event)
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
     * @param AggregateRootId $aggregateRootId
     * @param Generator       $events
     *
     * @return static
     * @see AggregateRoot::reconstituteFromEvents
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot
    {
        /** @var AggregateRoot|static $aggregateRoot */
        $aggregateRoot = new static($aggregateRootId);

        /** @var object $event */
        foreach ($events as $event) {
            $aggregateRoot->apply($event);
        }

        return $aggregateRoot;
    }
}
