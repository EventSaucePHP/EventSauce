<?php

namespace EventSauce\EventSourcing;

final class Message
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var array
     */
    private $metadata;

    public function __construct(Event $event, array $metadata = [])
    {
        $this->event = $event;
        $this->metadata = $metadata;
    }

    public function aggregateRootId(): AggregateRootId
    {
        return $this->event->aggregateRootId();
    }

    public function withMetadata(string $key, $value)
    {
        $clone = clone $this;
        $clone->metadata[$key] = $value;

        return $clone;
    }

    public function metadataValue(string $key)
    {
        return $this->metadata[$key] ?? null;
    }

    public function event(): Event
    {
        return $this->event;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }
}