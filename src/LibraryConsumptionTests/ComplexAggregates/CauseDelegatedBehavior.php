<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ComplexAggregates;

use EventSauce\EventSourcing\DummyAggregateRootId;

class CauseDelegatedBehavior
{
    /**
     * @var DummyAggregateRootId
     */
    private $id;

    public function __construct(DummyAggregateRootId $id)
    {
        $this->id = $id;
    }

    public function id(): DummyAggregateRootId
    {
        return $this->id;
    }
}
