<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\Exceptions;

class SorryCantAddProduct extends \Exception
{

    public static function becauseThePriceHasChanged(): self
    {
        return new self();
    }
}
