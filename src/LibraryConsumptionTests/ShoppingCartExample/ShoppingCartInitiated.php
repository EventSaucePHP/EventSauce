<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class ShoppingCartInitiated implements SerializablePayload
{

    public function __construct()
    {
    }

    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new self();
    }
}
