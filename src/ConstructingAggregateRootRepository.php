<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

/**
 * @deprecated use EventSourcedAggregateRootRepository::class instead
 *
 * @template T of AggregateRoot
 *
 * @extends EventSourcedAggregateRootRepository<T>
 */
class ConstructingAggregateRootRepository extends EventSourcedAggregateRootRepository
{
}
