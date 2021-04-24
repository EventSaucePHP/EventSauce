<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

/**
 * @template T of AggregateRoot
 */
interface AggregateRootRepository
{
    /**
     * @phpstan-return T
     *
     * @throws UnableToReconstituteAggregateRoot
     * @throws UnableToRetrieveMessages
     */
    public function retrieve(AggregateRootId $aggregateRootId): object;

    /**
     * @phpstan-param T $aggregateRoot
     *
     * @throws UnableToPersistMessages
     * @throws UnableToDispatchMessages
     */
    public function persist(object $aggregateRoot): void;

    /**
     * @throws UnableToPersistMessages
     * @throws UnableToDispatchMessages
     */
    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events): void;
}
