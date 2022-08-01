<?php

namespace EventSauce\EventSourcing;

final class OffsetCursor implements PaginationCursor
{
    private int $offset;

    private function __construct(int $offset = 0)
    {
        $this->offset = $offset;
    }

    public static function fromStart(): self
    {
        return new self(0);
    }

    public function toString(): string
    {
        return (string) $this->offset;
    }

    public static function fromString(string $cursor): static
    {
        return new self((int) $cursor);
    }

    public static function withOffset(int $offset): self
    {
        return new self($offset);
    }

    public function plusOffset(int $offset): self
    {
        return new self($this->offset + $offset);
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
