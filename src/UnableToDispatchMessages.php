<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;
use Throwable;

class UnableToDispatchMessages extends RuntimeException
{
    public static function dueTo(string $reason, Throwable $previous = null): self
    {
        return new self("Unable to dispatch messages. {$reason}", 0, $previous);
    }
}
