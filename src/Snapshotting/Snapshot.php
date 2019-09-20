<?php

namespace EventSauce\EventSourcing\Snapshotting;

use EventSauce\EventSourcing\AggregateRootId;

class Snapshot
{
    /**
     * @var AggregateRootId
     */
    private $aggregateRootId;

    /**
     * @var int
     */
    private $aggregateRootVersion;

    /**
     * @var mixed
     */
    private $state;

    public function __construct(
        AggregateRootId $aggregateRootId,
        int $aggregateRootVersion,
        $state
    ) {
        $this->aggregateRootId = $aggregateRootId;
        $this->aggregateRootVersion = $aggregateRootVersion;
        $this->state = $state;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->aggregateRootId;
    }

    public function aggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    public function state()
    {
        return $this->state;
    }
}
