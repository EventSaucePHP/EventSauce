<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

final class AggregateRootRepository
{
    /**
     * @var AggregateRootFactory
     */
    private $aggregateRootFactory;

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
        AggregateRootFactory $aggregateRootFactory,
        MessageRepository $messageRepository,
        MessageDispatcher $dispatcher = null,
        MessageDecorator $decorator = null
    ) {
        $this->repository = $messageRepository;
        $this->dispatcher = $dispatcher ?: new SynchronousMessageDispatcher();
        $this->decorator = $decorator ?: new DefaultHeadersDecorator();
        $this->aggregateRootFactory = $aggregateRootFactory;
    }

    public function retrieve(AggregateRootId $aggregateRootId): AggregateRoot
    {
        return $this->aggregateRootFactory->reconstituteFromEvents(
            $aggregateRootId,
            $this->retrieveAllEvents($aggregateRootId)
        );
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
        $this->persistEvents(
            $aggregateRoot->aggregateRootId(),
            $aggregateRoot->aggregateRootVersion(),
            ...$aggregateRoot->releaseEvents()
        );
    }

    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events)
    {
        $metadata = [Header::AGGREGATE_ROOT_ID => $aggregateRootId];
        $messages = array_map(function (object $event) use ($metadata, &$aggregateRootVersion) {
            return $this->decorator->decorate(new Message(
                $event,
                $metadata + [Header::AGGREGATE_ROOT_VERSION => ++$aggregateRootVersion]
            ));
        }, $events);

        $this->repository->persist(...$messages);
        $this->dispatcher->dispatch(...$messages);
    }
}
