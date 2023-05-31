<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Subscriptions;

class PartitionedCheckpoint implements Checkpoint
{
    private function __construct(
        private string $partitionKey,
        private int $offset,
    ) {
    }

    public static function fromOrigin(string $partitionKey): static
    {
        return new PartitionedCheckpoint($partitionKey, 0);
    }

    public function withOffset(int $offset): static
    {
        return new static($this->partitionKey, $offset);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getPartitionKey(): string
    {
        return $this->partitionKey;
    }
}
