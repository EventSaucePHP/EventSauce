<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events;

use EventSauce\EventSourcing\Serialization\SerializablePayload;

class CartCheckedOut implements SerializablePayload
{

    public function toPayload(): array
    {
        return [];
    }

    public static function fromPayload(array $payload): self
    {
        return new self();
    }
}
