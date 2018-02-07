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

    /**
     * @var MessageDispatcher
     */
    private $dispatcher;

    public function __construct(MessageDispatcher $dispatcher = null)
    {
        $this->lastCommit = [];
        $this->dispatcher = $dispatcher ?: new SynchronousMessageDispatcher();
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

        $this->dispatcher->dispatch(... $messages);
    }

    public function retrieveAll(AggregateRootId $id): Generator
    {
        yield from $this->messages[$id->toString()] ?? [];
    }
}