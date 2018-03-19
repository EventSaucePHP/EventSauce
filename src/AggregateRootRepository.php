<?php

declare(strict_types=1);

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

    /**
     * @param string                 $aggregateRootClassName
     * @param MessageRepository      $messageRepository
     * @param MessageDispatcher|null $dispatcher
     * @param MessageDecorator|null  $decorator
     */
    public function __construct(
        string $aggregateRootClassName,
        MessageRepository $messageRepository,
        MessageDispatcher $dispatcher = null,
        MessageDecorator $decorator = null
    ) {
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->repository = $messageRepository;
        $this->dispatcher = $dispatcher ?: new SynchronousMessageDispatcher();
        $this->decorator = $decorator ?: new DefaultHeadersDecorator();
    }

    /**
     * @param AggregateRootId $aggregateRootId
     *
     * @return AggregateRoot
     */
    public function retrieve(AggregateRootId $aggregateRootId): AggregateRoot
    {
        /** @var AggregateRoot $className */
        $className = $this->aggregateRootClassName;
        $events = $this->retrieveAllEvents($aggregateRootId);

        return $className::reconstituteFromEvents($aggregateRootId, $events);
    }

    /**
     * @param AggregateRootId $aggregateRootId
     *
     * @return Generator
     */
    private function retrieveAllEvents(AggregateRootId $aggregateRootId): Generator
    {
        /** @var Message $message */
        foreach ($this->repository->retrieveAll($aggregateRootId) as $message) {
            yield $message->event();
        }
    }

    /**
     * @param AggregateRoot $aggregateRoot
     */
    public function persist(AggregateRoot $aggregateRoot)
    {
        $this->persistEvents(
            $aggregateRoot->aggregateRootId(),
            $aggregateRoot->aggregateRootVersion(),
            ...$aggregateRoot->releaseEvents()
        );
    }

    /**
     * @param AggregateRootId $aggregateRootId
     * @param int             $aggregateRootVersion
     * @param Event[]         ...$events
     */
    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, Event ...$events)
    {
        $metadata = [Header::AGGREGATE_ROOT_ID => $aggregateRootId];
        $messages = array_map(function (Event $event) use ($metadata, &$aggregateRootVersion) {
            return $this->decorator->decorate(new Message(
                $event,
                $metadata + [Header::AGGREGATE_ROOT_VERSION => ++$aggregateRootVersion]
            ));
        }, $events);

        $this->repository->persist(...$messages);
        $this->dispatcher->dispatch(...$messages);
    }
}
