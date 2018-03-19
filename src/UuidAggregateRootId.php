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

    /**
     * @param string $identifier
     */
    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->identifier;
    }

    /**
     * @return UuidInterface
     */
    public function toUuid(): UuidInterface
    {
        return Uuid::fromString($this->identifier);
    }

    /**
     * @return UuidAggregateRootId
     */
    public static function create(): UuidAggregateRootId
    {
        return new UuidAggregateRootId(Uuid::uuid4()->toString());
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static($aggregateRootId);
    }
}
