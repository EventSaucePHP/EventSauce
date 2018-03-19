<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\AggregateRootBehaviour;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\Event;
use Generator;

trait ConstructionBehaviour
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
     * @param AggregateRootId $aggregateRootId
     */
    public function __construct(AggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

    /**
     * @return AggregateRootId
     */
    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    /**
     * @return int
     */
    public function aggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    /**
     * @param AggregateRootId $aggregateRootId
     * @param Generator       $events
     *
     * @return AggregateRoot|static
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot
    {
        $aggregateRoot = new static($aggregateRootId);

        /** @var Event $event */
        foreach ($events as $event) {
            $aggregateRoot->apply($event);
            ++$aggregateRoot->aggregateRootVersion;
        }

        return $aggregateRoot;
    }

    /**
     * @param Event $event
     */
    abstract protected function apply(Event $event);
}
