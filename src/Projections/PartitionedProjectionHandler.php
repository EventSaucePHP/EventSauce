<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\PaginationCursor;

class PartitionedProjectionHandler
{
    public function __construct(
        private ProjectionHandler $projectionHandler,
        private ProjectionId $projectionId,
        private Partitioner $partitioner,
        private PaginationCursor $initialCursor,
    ) {
    }

    public function handle(Message $message): void
    {
        $this->projectionHandler->handle($this->projectionId, $this->initialCursor);
    }
}
