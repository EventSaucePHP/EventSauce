<?php

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
    protected $aggregateRootId;

    public function __construct(AggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    /**
     * @param AggregateRootId $aggregateRootId
     * @param Generator           $events
     *
     * @return static
     */
    public static function reconstituteFromEvents(AggregateRootId $aggregateRootId, Generator $events): AggregateRoot
    {
        $aggregateRoot = new static($aggregateRootId);

        /** @var Event $event */
        foreach ($events as $event) {
            $aggregateRoot->apply($event);
        }

        return $aggregateRoot;
    }

    abstract protected function apply(Event $event);
}