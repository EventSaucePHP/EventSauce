<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Serialization\SerializableEvent;
use function compact;

class EventStub implements SerializableEvent
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

    public static function fromPayload(array $payload): SerializableEvent
    {
        return new static($payload['value']);
    }

    public static function create(string $value = null)
    {
        return static::fromPayload(compact('value'));
    }
}
