<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;

final class UnableToReconstructAggregateRoot extends RuntimeException implements EventSauceException
{
    public static function becauseItHasNoHistory(): UnableToReconstructAggregateRoot
    {
        return new UnableToReconstructAggregateRoot('The aggregate root has no recorded history.');
    }
}
