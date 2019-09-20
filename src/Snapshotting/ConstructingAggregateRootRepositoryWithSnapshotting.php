<?php

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\ConstructingAggregateRootRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageDecorator;
use EventSauce\EventSourcing\MessageDispatcher;
use Generator;

class ConstructingAggregateRootRepositoryWithSnapshotting
    extends ConstructingAggregateRootRepository
    implements AggregateRootRepositoryWithSnapshotting
{
    /**
     * @var SnapshotRepository
     */
    private $snapshotRepository;

    /**
     * @var SeekableMessageRepository
     */
    private $messageRepository;

    public function __construct(
        string $aggregateRootClassName,
        SeekableMessageRepository $messageRepository,
        SnapshotRepository $snapshotRepository,
        MessageDispatcher $dispatcher = null,
        MessageDecorator $decorator = null
    ) {
        parent::__construct($aggregateRootClassName, $messageRepository, $dispatcher, $decorator);
        $this->messageRepository = $messageRepository;
        $this->snapshotRepository = $snapshotRepository;
    }

    public function retrieveFromSnapshot(AggregateRootId $aggregateRootId): object
    {
        $snapshot = $this->snapshotRepository->retrieve($aggregateRootId);

        if ( ! $snapshot instanceof Snapshot) {
            return $this->retrieve($aggregateRootId);
        }

        /** @var AggregateRootWithSnapshotting $className */
        $className = $this->aggregateRootClassName;
        $events = $this->retrieveAllEventsAfterVersion($aggregateRootId, $snapshot->aggregateRootVersion());

        return $className::reconstituteFromSnapshotAndEvents($snapshot, $events);
    }

    public function storeSnapshot(AggregateRootWithSnapshotting $aggregateRoot): void
    {
        $snapshot = $aggregateRoot->createSnapshot();
        $this->snapshotRepository->persist($snapshot);
    }

    private function retrieveAllEventsAfterVersion(AggregateRootId $aggregateRootId, int $version)
    {
        /** @var Message[]|Generator $messages */
        $messages = $this->messageRepository->retrieveAllAfterVersion($aggregateRootId, $version);

        foreach ($messages as $message) {
            yield $message->event();
        }

        return $messages->getReturn();
    }


}
