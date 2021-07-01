<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;
use Throwable;

final class UnableToRetrieveMessages extends RuntimeException implements EventSauceException
{
    public static function dueTo(string $reason, Throwable $previous = null): static
    {
        return new static("Unable to retrieve messages. {$reason}", 0, $previous);
    }
}
