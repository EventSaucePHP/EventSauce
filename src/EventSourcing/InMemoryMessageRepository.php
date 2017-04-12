<?php

namespace EventSauce\EventSourcing;

use Generator;

class InMemoryMessageRepository implements MessageRepository
{
    /**
     * @var Message[]
     */
    private $messages = [];

    /**
     * @var Event[]
     */
    private $lastCommit;

    public function __construct()
    {
        $this->lastCommit = [];
    }

    /**
     * @return Event[]
     */
    public function lastCommit(): array
    {
        return $this->lastCommit;
    }

    public function purgeLastCommit()
    {
        $this->lastCommit = [];
    }

    public function persist(Message ... $messages)
    {
        $this->lastCommit = [];

        /** @var Message $event */
        foreach ($messages as $message) {
            $event = $message->event();
            $this->messages[$message->aggregateRootId()->toString()][] = $message;
            $this->lastCommit[] = $event;
        }
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        yield from $this->messages[$id->toString()] ?? [];
    }
}