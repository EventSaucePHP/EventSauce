<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class LightSwitchWasFlipped implements SerializablePayload
{
    const ON = true;
    const OFF = false;

    /**
     * @var bool
     */
    private $state;

    private function __construct(bool $state)
    {
        $this->state = $state;
    }

    public function state(): bool
    {
        return $this->state;
    }

    public static function on(): LightSwitchWasFlipped
    {
        return new static(self::ON);
    }

    public static function off(): LightSwitchWasFlipped
    {
        return new static(self::OFF);
    }

    public function toPayload(): array
    {
        return ['state' => $this->state];
    }

    public static function fromPayload(array $payload): self
    {
        return new static($payload['state']);
    }
}
