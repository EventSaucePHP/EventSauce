<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Exceptions;

class SorryCantCheckout extends \Exception
{

    public static function becauseThereAreNoProductsInCart(): self
    {
        return new self();
    }
}
