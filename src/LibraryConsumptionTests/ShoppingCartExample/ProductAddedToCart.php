<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

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

    public static function fromPayload(array $payload): static
    {
        return new static(
            ProductId::fromString($payload['productId']),
            $payload['price']
        );
    }
}
