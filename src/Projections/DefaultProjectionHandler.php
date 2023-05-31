<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\PaginationCursor;

class DefaultProjectionHandler
{
    public function __construct(
        private ProjectionHandler $projectionHandler,
        private ProjectionId $projectionId,
        private PaginationCursor $initialCursor,
    ) {
    }

    public function handle(): void
    {
        $this->projectionHandler->handle($this->projectionId, $this->initialCursor);
    }
}
