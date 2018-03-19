<?php

declare(strict_types=1);

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
    private $headers;

    /**
     * @param Event $event
     * @param array $metadata
     */
    public function __construct(Event $event, array $metadata = [])
    {
        $this->event = $event;
        $this->headers = $metadata;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return Message
     */
    public function withHeader(string $key, $value): Message
    {
        $clone = clone $this;
        $clone->headers[$key] = $value;

        return $clone;
    }

    /**
     * @param array $headers
     *
     * @return Message
     */
    public function withHeaders(array $headers): Message
    {
        $clone = clone $this;
        $clone->headers = $headers + $clone->headers;

        return $clone;
    }

    /**
     * @return AggregateRootId|null
     */
    public function aggregateRootId(): ?AggregateRootId
    {
        return $this->headers[Header::AGGREGATE_ROOT_ID] ?? null;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function header(string $key)
    {
        return $this->headers[$key] ?? null;
    }

    /**
     * @return array
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @return Event
     */
    public function event(): Event
    {
        return $this->event;
    }
}
