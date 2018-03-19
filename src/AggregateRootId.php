<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface AggregateRootId
{
    /**
     * @return string
     */
    public function toString(): string;

    /**
     * @param string $aggregateRootId
     *
     * @return AggregateRootId
     */
    public static function fromString(string $aggregateRootId): AggregateRootId;
}
