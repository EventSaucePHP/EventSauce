<?php

namespace EventSauce\EventSourcing\Projections;

use EventSauce\EventSourcing\Subscriptions\Checkpoint;

interface ProjectionStatusRepository
{
    public function getCursor(ProjectionId $projectionId): ?Checkpoint;

    /**
     * @throws CantLockProjection
     */
    public function getCheckpointAndLock(ProjectionId $projectionId): ?Checkpoint;

    public function persistCheckpointAndRelease(ProjectionId $projectionId, Checkpoint $checkpoint): void;
}
