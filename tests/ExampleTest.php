<?php

use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ProductAddedToCart;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ProductId;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ShoppingCart;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\ShoppingCartInitiated;
use EventSauce\EventSourcing\LibraryConsumptionTests\ShoppingCartExample\SorryCantCheckout;
use PeventSauce\ShoppingCartTestCase;
use function EventSauce\EventSourcing\PestTooling\expectToFail;
use function EventSauce\EventSourcing\PestTooling\given;
use function EventSauce\EventSourcing\PestTooling\nothingShouldHaveHappened;
use function EventSauce\EventSourcing\PestTooling\when;

uses(ShoppingCartTestCase::class);

it('can add an item to a shopping cart', function () {
    given(
        new ShoppingCartInitiated(),
    )->when(function (ShoppingCart $cart): void {
        $cart->addProduct(new ProductId('garlic sauce'), 250);
    })->then(
        new ProductAddedToCart(new ProductId('garlic sauce'), 250)
    );
});

it('fails when checking out without items', function () {
    given(new ShoppingCartInitiated());
    when(function (ShoppingCart $cart): void {
        $cart->checkout();
    });
    expectToFail(SorryCantCheckout::becauseThereAreNoProductsInCart());
    nothingShouldHaveHappened();
});
