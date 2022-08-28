<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\AggregateRootId;

final class ShoppingCartId implements AggregateRootId
{
    private function __construct(private string $aggregateRootId)
    {
    }

    public function toString(): string
    {
        return $this->aggregateRootId;
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new static($aggregateRootId);
    }

    public static function create(): static
    {
        return static::fromString(bin2hex(random_bytes(25)));
    }
}
