<?php

namespace EventSauce\EventSourcing\Subscriptions;

class OffsetCheckpoint implements Checkpoint
{
    private function __construct(
        private int $offset,
    ) {
    }

    public static function forOffset(int $offset): static
    {
        return new static($offset);
    }

    public static function fromStart(): static
    {
        return new static(0);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function toString(): string
    {
        return (string) $this->offset;
    }

    public static function fromString(string $string): static
    {
        return new static((int) $string);
    }
}
