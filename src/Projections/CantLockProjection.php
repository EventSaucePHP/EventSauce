<?php

namespace EventSauce\EventSourcing\Projections;

class CantLockProjection extends \Exception
{
    public static function becauseItIsAlreadyLocked(string $projectionId): self
    {
        return new self("Projection $projectionId is already locked");
    }
}
