<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;

final class UnableToInflectEventType extends RuntimeException
{
    public static function mappingIsNotDefined(string $eventType): UnableToInflectEventType
    {
        return new UnableToInflectEventType("No mapping defined for event type: $eventType");
    }
}
