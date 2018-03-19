<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use function compact;

class EventStub implements Event
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function toPayload(): array
    {
        return ['value' => $this->value];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromPayload(array $payload): Event
    {
        return new static($payload['value']);
    }

    /**
     * @param string|null $value
     *
     * @return Event
     */
    public static function create(string $value = null)
    {
        return static::fromPayload(compact('value'));
    }
}
