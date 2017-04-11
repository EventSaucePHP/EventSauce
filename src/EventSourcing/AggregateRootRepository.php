<?php

namespace EventSauce\EventSourcing;

use Generator;

final class AggregateRootRepository
{
    /**
     * @var string
     */
    private $aggregateRootClassName;

    /**
     * @var MessageRepository
     */
    private $eventStore;

    /**
     * @var MessageDecorator
     */
    private $processor;

    public function __construct(string $aggregateRootClassName, MessageRepository $eventStore, MessageDecorator $processors)
    {
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->eventStore = $eventStore;
        $this->processor = $processors;
    }

    public function retrieve(AggregateRootId $aggregateRootId): AggregateRoot
    {
        /** @var AggregateRoot $className */
        $className = $this->aggregateRootClassName;
        $events = $this->retrieveAllEvents($aggregateRootId);

        return $className::reconstituteFromEvents($aggregateRootId, $events);
    }

    private function retrieveAllEvents(AggregateRootId $aggregateRootId): Generator
    {
        /** @var Message $message */
        foreach ($this->eventStore->retrieveAll($aggregateRootId) as $message) {
            yield $message->event();
        }
    }

    public function persist(Event ... $events)
    {
        $messages = array_map(function (Event $event) {
            return $this->processor->decorate(new Message($event));
        }, $events);

        $this->eventStore->persist(... $messages);
    }
}