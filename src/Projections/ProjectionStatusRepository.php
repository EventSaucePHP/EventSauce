<?php

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\PaginationCursor;

interface ProjectionStatusRepository
{
    public function getCursor(ProjectionId $projectionId): ?PaginationCursor;

    /**
     * @throws CantLockProjection
     */
    public function getCursorAndLock(ProjectionId $projectionId): ?PaginationCursor;

    public function persistCursorAndRelease(ProjectionId $projectionId, PaginationCursor $cursor): void;
}
