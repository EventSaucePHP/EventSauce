<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;
use Throwable;

final class UnableToDispatchMessages extends RuntimeException implements EventSauceException
{
    public static function dueTo(string $reason, Throwable $previous = null): static
    {
        return new static("Unable to dispatch messages. {$reason}", 0, $previous);
    }
}
