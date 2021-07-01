<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class LightSwitchWasFlipped implements SerializablePayload
{
    public const ON = true;
    public const OFF = false;

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

    public static function on(): static
    {
        return new static(self::ON);
    }

    public static function off(): static
    {
        return new static(self::OFF);
    }

    public function toPayload(): array
    {
        return ['state' => $this->state];
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload['state']);
    }
}
