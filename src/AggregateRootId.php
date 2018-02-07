<?php

namespace EventSauce\EventSourcing;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class AggregateRootId
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

    public static function create(): AggregateRootId
    {
        return new AggregateRootId(Uuid::uuid4()->toString());
    }
}