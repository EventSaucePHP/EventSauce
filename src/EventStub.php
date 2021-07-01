<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use function compact;

/**
 * @testAsset
 * @codeCoverageIgnore
 */
final class EventStub implements SerializablePayload
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

    public function getValue(): string
    {
        return $this->value;
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload['value']);
    }

    public static function create(string $value = null): static
    {
        return static::fromPayload(compact('value'));
    }
}
