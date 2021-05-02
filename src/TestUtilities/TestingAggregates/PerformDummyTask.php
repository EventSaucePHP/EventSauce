<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use EventSauce\EventSourcing\DummyAggregateRootId;

/**
 * @testAsset
 */
class PerformDummyTask implements DummyCommand
{
    private DummyAggregateRootId $aggregateRootId;

    public function __construct(DummyAggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

    public function aggregateRootId(): DummyAggregateRootId
    {
        return $this->aggregateRootId;
    }
}
