<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface AggregateRootId
{
    /** @return non-empty-string */
    public function toString(): string;

    /**
     * @param non-empty-string $aggregateRootId
     */
    public static function fromString(string $aggregateRootId): static;
}
