<?php

namespace EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample;

final class SorryCantAddProduct extends \Exception
{
    public static function becauseThePriceHasChanged(): static
    {
        return new static('Item price has changed.');
    }
}
