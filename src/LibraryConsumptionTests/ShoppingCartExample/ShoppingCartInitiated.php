<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

final class ShoppingCartInitiated implements SerializablePayload
{
    public function __construct()
    {
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): static
    {
        return new static();
    }
}
