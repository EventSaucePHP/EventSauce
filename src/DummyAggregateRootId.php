<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

/**
 * @testAsset
 */
final class DummyAggregateRootId implements AggregateRootId
{
    /** @var non-empty-string */
    private string $identifier;

    /**
     * @param non-empty-string $identifier
     */
    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function toString(): string
    {
        return $this->identifier;
    }

    public static function generate(): DummyAggregateRootId
    {
        return new DummyAggregateRootId(bin2hex(random_bytes(25)));
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new static($aggregateRootId);
    }
}
