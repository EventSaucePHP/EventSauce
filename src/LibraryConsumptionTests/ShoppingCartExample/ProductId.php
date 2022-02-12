<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

final class ProductId
{
    public function __construct(protected string $id)
    {

    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $id): static
    {
        return new static($id);
    }
}
