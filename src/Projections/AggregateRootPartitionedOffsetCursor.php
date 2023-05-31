<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\PaginationCursor;

class AggregateRootPartitionedOffsetCursor implements PaginationCursor
{
    private function __construct(
        private AggregateRootId $aggregateRootId,
        private int $offset
    ) {
    }

    public static function fromStart(AggregateRootId $aggregateRootId): static
    {
        return new static(
            aggregateRootId: $aggregateRootId,
            offset: 0
        );
    }

    public function toString(): string
    {
        return implode('###', [
            get_class($this->aggregateRootId),
            $this->aggregateRootId->toString(),
            (string) $this->offset,
        ]);
    }

    public static function fromString(string $cursor): static
    {
        $cursor = explode('###', $cursor);
        return new static(
            aggregateRootId: new $cursor[0]($cursor[1]),
            offset: (int) $cursor[2]
        );
    }

    public function isAtStart(): bool
    {
        return $this->offset === 0;
    }
}
