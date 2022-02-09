<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Events;

use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ProductId;
use EventSauce\EventSourcing\Serialization\SerializablePayload;

class ProductAddedToCart implements SerializablePayload
{

    public function __construct(public ProductId $productId, public int $price)
    {
    }

    public function toPayload(): array
    {
        return [
            'productId' => $this->productId->toString(),
            'price' => $this->price,
        ];
    }

    public static function fromPayload(array $payload): self
    {
        return new self(
            ProductId::fromString($payload['productId']),
            $payload['price']
        );
    }
}
