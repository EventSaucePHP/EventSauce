<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;

final class UnableToInflectClassName extends RuntimeException
{
    public static function mappingIsNotDefined(string $className): UnableToInflectClassName
    {
        return new UnableToInflectClassName("No mapping defined for class name: $className");
    }
}
