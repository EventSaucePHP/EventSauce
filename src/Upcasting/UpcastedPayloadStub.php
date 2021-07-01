<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Upcasting;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

/**
 * @testAsset
 */
final class UpcastedPayloadStub implements SerializablePayload
{
    private string $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    public function toPayload(): array
    {
        return ['property' => $this->property];
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload['property'] ?? 'undefined');
    }
}
