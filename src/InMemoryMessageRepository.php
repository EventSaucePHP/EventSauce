<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

final class InMemoryMessageRepository implements MessageRepository
{
    /**
     * @var Message[]
     */
    private array $messages = [];

    /**
     * @var object[]
     */
    private array $lastCommit = [];

    /**
     * @return object[]
     */
    public function lastCommit(): array
    {
        return $this->lastCommit;
    }

    public function purgeLastCommit(): void
    {
        $this->lastCommit = [];
    }

    public function persist(Message ...$messages): void
    {
        $this->lastCommit = [];

        /** @var Message $message */
        foreach ($messages as $message) {
            $this->messages[] = $message;
            $this->lastCommit[] = $message->payload();
        }
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        $lastMessage = null;

        foreach ($this->messages as $message) {
            if ($id->toString() === $message->aggregateRootId()?->toString()) {
                yield $message;
                $lastMessage = $message;
            }
        }

        return $lastMessage instanceof Message ? $lastMessage->aggregateVersion() : 0;
    }

    public function retrieveAllAfterVersion(AggregateRootId $id, int $aggregateRootVersion): Generator
    {
        $lastMessage = null;

        foreach ($this->messages as $message) {
            if ($id->toString() === $message->aggregateRootId()?->toString()
                && $message->header(Header::AGGREGATE_ROOT_VERSION) > $aggregateRootVersion) {
                yield $message;
                $lastMessage = $message;
            }
        }

        return $lastMessage instanceof Message ? $lastMessage->aggregateVersion() : 0;
    }
}
