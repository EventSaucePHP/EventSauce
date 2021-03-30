<?php

namespace EventSauce\EventSourcing;

use RuntimeException;
use Throwable;

class UnableToRetrieveMessages extends RuntimeException
{
    public static function dueTo(string $reason, Throwable $previous): self
    {
        return new self("Unable to retrieve messages. {$reason}", 0, $previous);
    }
}
