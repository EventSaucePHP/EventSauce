<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use EventSauce\EventSourcing\DummyAggregateRootId;

/**
 * @testAsset
 */
interface DummyCommand
{
    public function aggregateRootId(): DummyAggregateRootId;
}
