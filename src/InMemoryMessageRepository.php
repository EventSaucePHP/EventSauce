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
    private $lastCommit = [];

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

    public function persist(AggregateRootId $id, Message ... $messages)
    {
        $this->lastCommit = [];

        /** @var Message $event */
        foreach ($messages as $message) {
            $event = $message->event();
            $this->messages[$id->toString()][] = $message;
            $this->lastCommit[] = $event;
        }
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        yield from $this->messages[$id->toString()] ?? [];
    }
}