<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface AggregateRootId
{
    public function toString(): string;

    public static function fromString(string $aggregateRootId): static;
}
