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

    /**
     * @var MessageDispatcher
     */
    private $dispatcher;

    public function __construct(
        string $aggregateRootClassName,
        MessageRepository $messageRepository,
        MessageDispatcher $dispatcher = null,
        MessageDecorator $decorator = null
    ) {
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->repository = $messageRepository;
        $this->dispatcher = $dispatcher ?: new SynchronousMessageDispatcher();
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
        $this->persistEvents($aggregateRoot->aggregateRootId(), ...$aggregateRoot->releaseEvents());
    }

    public function persistEvents(AggregateRootId $aggregateRootId, Event ... $events)
    {
        $metadata = ['aggregate_root_id' => $aggregateRootId];
        $messages = array_map(function (Event $event) use ($metadata) {
            return $this->decorator->decorate(new Message($event, $metadata));
        }, $events);

        $this->repository->persist(... $messages);
        $this->dispatcher->dispatch(... $messages);
    }
}