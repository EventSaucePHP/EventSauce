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

    public function __construct(string $aggregateRootClassName, MessageRepository $messageRepository, MessageDecorator $decorator = null)
    {
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->repository = $messageRepository;
        $this->decorator = $decorator ?: new DelegatingMessageDecorator();
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

    public function persist(AggregateRoot $aggregateRoot)
    {
        $this->persistEvents(...$aggregateRoot->releaseEvents());
    }

    public function persistEvents(Event ... $events)
    {
        $messages = array_map(function (Event $event) {
            return $this->decorator->decorate(new Message($event));
        }, $events);

        $this->repository->persist(... $messages);
    }
}