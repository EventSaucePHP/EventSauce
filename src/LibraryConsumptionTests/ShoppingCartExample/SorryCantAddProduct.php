<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

class SorryCantAddProduct extends \Exception
{

    public static function becauseThePriceHasChanged(): self
    {
        return new self();
    }
}
