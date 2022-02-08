<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\AggregateWithDelegatedBehavior;

use EventSauce\EventSourcing\DummyAggregateRootId;

class CauseDelegatedBehavior
{
    public function __construct(private DummyAggregateRootId $id)
    {
    }

    public function id(): DummyAggregateRootId
    {
        return $this->id;
    }
}
