<?php

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\UuidAggregateRootId;
use EventSauce\EventSourcing\Command;

class ExceptionInducingCommand implements Command
{
    /**
     * @var UuidAggregateRootId
     */
    private $aggregateRootId;

    public function __construct(UuidAggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }
}