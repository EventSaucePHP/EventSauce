<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;

class DummyAggregateRootId implements AggregateRootId
{
    /**
     * @var string
     */
    private $identifier;

    private function __construct(string $identifier)
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
     * @param string $aggregateRootId
     *
     * @return static
     */
    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static($aggregateRootId);
    }

    public static function generate(): AggregateRootId
    {
        return new static(Uuid::uuid4()->toString());
    }
}
