<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Projections;


use EventSauce\EventSourcing\Subscriptions\Checkpoint;

class InMemoryProjectionStatusRepository implements ProjectionStatusRepository
{
    private array $state = [];
    private array $locks = [];

    public function getCursor(ProjectionId $projectionId): ?Checkpoint
    {
        return $this->state[$projectionId->toString()] ?? null;
    }

    /**
     * @throws CantLockProjection
     */
    public function getCheckpointAndLock(ProjectionId $projectionId): ?Checkpoint
    {
        if (array_key_exists($projectionId->toString(), $this->locks)) {
            throw CantLockProjection::becauseItIsAlreadyLocked($projectionId->toString());
        }

        $this->locks[$projectionId->toString()] = true;

        return $this->state[$projectionId->toString()] ?? null;
    }

    public function persistCheckpointAndRelease(ProjectionId $projectionId, Checkpoint $checkpoint): void
    {
        unset($this->locks[$projectionId->toString()]);
        $this->state[$projectionId->toString()] = $checkpoint;
    }
}
