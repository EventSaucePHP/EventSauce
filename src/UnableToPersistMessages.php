<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;
use Throwable;

final class UnableToPersistMessages extends RuntimeException implements EventSauceException
{
    public static function dueTo(string $reason, Throwable $previous = null): self
    {
        return new self("Unable to persist messages. {$reason}", 0, $previous);
    }
}
