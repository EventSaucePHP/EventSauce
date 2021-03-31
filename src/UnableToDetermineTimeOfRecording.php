<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use RuntimeException;

final class UnableToDetermineTimeOfRecording extends RuntimeException implements EventSauceException
{
    public static function fromFormatAndHeader(string $format, mixed $header): self
    {
        return new self("Unable to determine time of recording from format \"{$format}\" and header \"{$header}\"");
    }
}
