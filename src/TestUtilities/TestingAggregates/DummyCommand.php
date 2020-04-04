<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\TestUtilities\TestingAggregates;

use EventSauce\EventSourcing\DummyAggregateRootId;

interface DummyCommand
{
    public function aggregateRootId(): DummyAggregateRootId;
}
