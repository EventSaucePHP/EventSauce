<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

class ProductId
{
    public function __construct(protected string $id)
    {

    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }
}
