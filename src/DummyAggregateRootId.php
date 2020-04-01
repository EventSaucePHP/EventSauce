<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

final class DummyAggregateRootId implements AggregateRootId
{
    /**
     * @var string
     */
    private $identifier;

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

    /**
     * @return static
     */
    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static($aggregateRootId);
    }
}
