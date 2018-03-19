<?php

declare(strict_types=1);

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

    /**
     * {@inheritdoc}
     */
    public function persist(Message ...$messages)
    {
        $this->lastCommit = [];

        /* @var Message $event */
        foreach ($messages as $message) {
            $this->messages[] = $message;
            $this->lastCommit[] = $message->event();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveAll(AggregateRootId $id): Generator
    {
        foreach ($this->messages as $message) {
            if ($id->toString() === $message->header(Header::AGGREGATE_ROOT_ID)->toString()) {
                yield $message;
            }
        }
    }
}
