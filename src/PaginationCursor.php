<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

interface PaginationCursor
{
    public function toString(): string;

    public static function fromString(string|null $cursor): static|null;

    public function isAtStart(): bool;
}
