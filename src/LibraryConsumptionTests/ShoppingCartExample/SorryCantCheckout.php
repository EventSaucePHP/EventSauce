<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

class SorryCantCheckout extends \Exception
{

    public static function becauseThereAreNoProductsInCart(): self
    {
        return new self();
    }
}
