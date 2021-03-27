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
     */
    public function retrieve(AggregateRootId $aggregateRootId): object;

    /**
     * @phpstan-param T $aggregateRoot
     */
    public function persist(object $aggregateRoot): void;

    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events): void;
}
