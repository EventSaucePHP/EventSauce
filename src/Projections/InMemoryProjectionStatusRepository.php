<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\PaginationCursor;

class InMemoryProjectionStatusRepository implements ProjectionStatusRepository
{
    private array $state = [];
    private array $locks = [];

    public function getCursor(ProjectionId $projectionId): ?PaginationCursor
    {
        return $this->state[$projectionId->toString()] ?? null;
    }

    /**
     * @throws CantLockProjection
     */
    public function getCursorAndLock(ProjectionId $projectionId): ?PaginationCursor
    {
        if (array_key_exists($projectionId->toString(), $this->locks)) {
            throw CantLockProjection::becauseItIsAlreadyLocked($projectionId->toString());
        }

        $this->locks[$projectionId->toString()] = true;

        return $this->state[$projectionId->toString()] ?? null;
    }

    public function persistCursorAndRelease(ProjectionId $projectionId, PaginationCursor $cursor): void
    {
        unset($this->locks[$projectionId->toString()]);
        $this->state[$projectionId->toString()] = $cursor;
    }
}
