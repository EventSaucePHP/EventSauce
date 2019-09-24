<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use Generator;

final class InMemoryMessageRepository implements MessageRepository
{
    /**
     * @var Message[]
     */
    private $messages = [];

    /**
     * @var object[]
     */
    private $lastCommit = [];

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

        /* @var Message $event */
        foreach ($messages as $message) {
            $this->messages[] = $message;
            $this->lastCommit[] = $message->event();
        }
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        $lastMessage = null;

        foreach ($this->messages as $message) {
            if ($id->toString() === $message->header(Header::AGGREGATE_ROOT_ID)->toString()) {
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
            if ($id->toString() === $message->header(Header::AGGREGATE_ROOT_ID)->toString()
                && $message->header(Header::AGGREGATE_ROOT_VERSION) > $aggregateRootVersion) {
                yield $message;
                $lastMessage = $message;
            }
        }

        return $lastMessage instanceof Message ? $lastMessage->aggregateVersion() : 0;
    }
}
