<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\AggregateRootId;

class ShoppingCartId implements AggregateRootId
{

    private function __construct(private string $aggregateRootId)
    {
    }

    public function toString(): string
    {
        return $this->aggregateRootId;
    }

    public static function fromString(string $aggregateRootId): self
    {
        return new self($aggregateRootId);
    }

    public static function create(): self
    {
        return self::fromString(bin2hex(random_bytes(25)));
    }
}
