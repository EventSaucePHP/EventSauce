<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use Generator;

final class ConstructingAggregateRootRepositoryWithSnapshotting implements AggregateRootRepositoryWithSnapshotting
{
    /**
     * @var string
     */
    private $aggregateRootClassName;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * @var SnapshotRepository
     */
    private $snapshotRepository;

    /**
     * @var AggregateRootRepository
     */
    private $regularRepository;

    public function __construct(
        string $aggregateRootClassName,
        MessageRepository $messageRepository,
        SnapshotRepository $snapshotRepository,
        AggregateRootRepository $regularRepository
    ) {
        $this->aggregateRootClassName = $aggregateRootClassName;
        $this->messageRepository = $messageRepository;
        $this->snapshotRepository = $snapshotRepository;
        $this->regularRepository = $regularRepository;
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

    private function retrieveAllEventsAfterVersion(AggregateRootId $aggregateRootId, int $version): Generator
    {
        /** @var Message[]|Generator $messages */
        $messages = $this->messageRepository->retrieveAllAfterVersion($aggregateRootId, $version);

        foreach ($messages as $message) {
            yield $message->event();
        }

        return $messages->getReturn();
    }

    public function retrieve(AggregateRootId $aggregateRootId): object
    {
        return $this->regularRepository->retrieve($aggregateRootId);
    }

    public function persist(object $aggregateRoot): void
    {
        $this->regularRepository->persist($aggregateRoot);
    }

    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events): void
    {
        $this->regularRepository->persistEvents($aggregateRootId, $aggregateRootVersion, ...$events);
    }
}
