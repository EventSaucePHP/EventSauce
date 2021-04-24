<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;
use Throwable;

final class UnableToReconstituteAggregateRoot extends RuntimeException implements EventSauceException
{
    public static function becauseOf(string $reason, Throwable $previous = null): UnableToReconstituteAggregateRoot
    {
        return new UnableToReconstituteAggregateRoot(
            'Unable to reconstruct aggregate root. ' . $reason, 0, $previous
        );
    }

    public static function becauseItHasNoHistory(): UnableToReconstituteAggregateRoot
    {
        return new UnableToReconstituteAggregateRoot('The aggregate root has no recorded history.');
    }
}
