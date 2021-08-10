<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use Generator;

/**
 * @template T of AggregateRootWithSnapshotting
 *
 * @template-implements AggregateRootRepositoryWithSnapshotting<T>
 */
final class ConstructingAggregateRootRepositoryWithSnapshotting implements AggregateRootRepositoryWithSnapshotting
{
    /**
     * @phpstan-param class-string<T> $regularRepository
     * @phpstan-param AggregateRootRepository<T> $regularRepository
     */
    public function __construct(
        private string $aggregateRootClassName,
        private MessageRepository $messageRepository,
        private SnapshotRepository $snapshotRepository,
        private AggregateRootRepository $regularRepository
    ) {
    }

    public function retrieveFromSnapshot(AggregateRootId $aggregateRootId): object
    {
        $snapshot = $this->snapshotRepository->retrieve($aggregateRootId);

        if ( ! $snapshot instanceof Snapshot) {
            return $this->retrieve($aggregateRootId);
        }

        /** @phpstan-var T $className */
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
            yield $message->payload();
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
