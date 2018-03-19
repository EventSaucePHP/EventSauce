<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

final class Message
{
    /**
     * @var object
     */
    private $event;

    /**
     * @var array
     */
    private $headers;

    public function __construct(object $event, array $metadata = [])
    {
        $this->event = $event;
        $this->headers = $metadata;
    }

    public function withHeader(string $key, $value): Message
    {
        $clone = clone $this;
        $clone->headers[$key] = $value;

        return $clone;
    }

    public function withHeaders(array $headers): Message
    {
        $clone = clone $this;
        $clone->headers = $headers + $clone->headers;

        return $clone;
    }

    public function aggregateRootId(): ?AggregateRootId
    {
        return $this->headers[Header::AGGREGATE_ROOT_ID] ?? null;
    }

    public function header(string $key)
    {
        return $this->headers[$key] ?? null;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function event(): object
    {
        return $this->event;
    }
}
