<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;

final class UnableToResolveTimeOfRecording extends RuntimeException implements EventSauceException
{
    public static function fromFormatAndHeader(string $format, mixed $header): static
    {
        return new static("Unable to determine time of recording from format \"{$format}\" and header \"{$header}\"");
    }
}
