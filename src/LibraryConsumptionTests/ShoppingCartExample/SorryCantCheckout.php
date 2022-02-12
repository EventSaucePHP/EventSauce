<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

class SorryCantCheckout extends \Exception
{
    public static function becauseThereAreNoProductsInCart(): static
    {
        return new static('No items in the shopping cart.');
    }
}
