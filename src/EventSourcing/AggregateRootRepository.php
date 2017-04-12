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
    private $repository;

    /**
     * @var MessageDecorator
     */
    private $decorator;

    public function __construct(string $aggregateRootClassName, MessageRepository $repository, MessageDecorator $decorator)
    {
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->repository = $repository;
        $this->decorator = $decorator;
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
        foreach ($this->repository->retrieveAll($aggregateRootId) as $message) {
            yield $message->event();
        }
    }

    public function persist(Event ... $events)
    {
        $messages = array_map(function (Event $event) {
            return $this->decorator->decorate(new Message($event));
        }, $events);

        $this->repository->persist(... $messages);
    }
}