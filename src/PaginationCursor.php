<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface PaginationCursor
{
    public function toString(): string;

    public static function fromString(string $cursor): static;

    public function isAtStart(): bool;
}
