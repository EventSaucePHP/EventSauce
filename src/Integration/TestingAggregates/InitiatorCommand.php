<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\TestingAggregates;

class InitiatorCommand
{
    /**
     * @var DummyAggregateRootId
     */
    private $aggregateRootId;

    public function __construct(DummyAggregateRootId $aggregateRootId)
    {
        $this->aggregateRootId = $aggregateRootId;
    }

    public function aggregateRootId(): DummyAggregateRootId
    {
        return $this->aggregateRootId;
    }
}
