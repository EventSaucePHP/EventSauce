<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function explode;

final class OffsetCursor implements PaginationCursor
{
    private function __construct(private int $limit = 100, private int $offset = 0)
    {
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public static function fromStart(int $limit = 100): self
    {
        return new self($limit, 0);
    }

    public static function fromOffset(int $offset, int $limit = 100): self
    {
        return new self($limit, $offset);
    }

    public function toString(): string
    {
        return $this->offset . '|' . $this->limit;
    }

    public static function fromString(string $cursor): static
    {
        [$offset, $limit] = explode('|', $cursor);

        return new self((int) $limit, (int) $offset);
    }

    public function withOffset(int $offset): self
    {
        $clone = clone $this;
        $clone->offset = $offset;

        return $clone;
    }

    public function withLimit(int $limit): self
    {
        $clone = clone $this;
        $clone->limit = $limit;

        return $clone;
    }

    public function plusOffset(int $offset): self
    {
        return $this->withOffset($this->offset + $offset);
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function isAtStart(): bool
    {
        return $this->offset === 0;
    }
}
