<?php

namespace EventSauce\EventSourcing;

use function compact;

class EventStub implements Event
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toPayload(): array
    {
        return ['value' => $this->value];
    }

    public static function fromPayload(array $payload): Event
    {
        return new static($payload['value']);
    }

    public static function create(string $value = null)
    {
        return static::fromPayload(compact('value'));
    }
}