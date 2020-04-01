<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UuidAggregateRootId implements AggregateRootId
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

    public function toUuid(): UuidInterface
    {
        return Uuid::fromString($this->identifier);
    }

    public static function create(): UuidAggregateRootId
    {
        return new UuidAggregateRootId(Uuid::uuid4()->toString());
    }

    /**
     * @return static
     */
    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static($aggregateRootId);
    }
}
