<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface AggregateRoot
{
    /**
     * @return AggregateRootId
     */
    public function aggregateRootId(): AggregateRootId;

    /**
     * @return int
     */
    public function aggregateRootVersion(): int;

    /**
     * @return object[]
     */
    public function releaseEvents(): array;
}
